<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteFormData;
use App\Admin\Databases\Website\SiteFormFields;
use App\Admin\Databases\Website\SiteForms;
use App\Admin\Databases\Website\SiteFormSubmissions;

use Carbon\Carbon;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\DB;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Payout;
use Stripe\Stripe;

class FormSubmissions extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Form Submissions');
             $tmpForm = SiteForms::find(request()->route()->parameter('fid'));

             if ($tmpForm)
             {
                 $content->description('Listed below are submitted information of ' . $tmpForm->title);
             }



            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    public function destroy($id,$sid)
    {
        DB::table('site_forms_data')->where('submission_id', '=' , $sid)->delete();
        DB::table('site_forms_submissioon')->where('id','=',$sid)->delete();
        return response(json_encode(['status'=>true,'message'=>"Delete succeeded !"]))->header('Content-Type', 'application/json');
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SiteFormSubmissions::class, function (Grid $grid) {

            $grid->model()->where('form_id','=', request()->route()->parameter('fid'));
            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $sub = SiteFormSubmissions::find($actions->getKey());

                $actions->disableEdit();

                if ($sub->stripe_charge != null)
                {
                    Stripe::setApiKey(env("STRIPE_SECRET"));
                    $ch = Charge::retrieve($sub->stripe_charge);
                    $cus = Customer::retrieve($ch->customer);

                    $actions->prepend('<a href="#" data-toggle="modal" data-target="#modal_'.$actions->getKey().'" title="View payment info"><i class="fa fa-credit-card"></i></a> 

                                        <div id="modal_'.$actions->getKey().'" class="modal fade in" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3 class="modal-title">Payment Information</h3>
      </div>
      <div class="modal-body">
        <h4>Payment Details</h4>
        <table class="table table-hover">
        <tbody>
        <tr>
        <td>ID</td>
        <td>'.$ch->id.'</td>
        </tr>
        <tr>
        <td>Status</td>
        <td>'.title_case($ch->status).'</td>
        </tr>
        <tr>
        <td>Amount</td>
        <td>'.($ch->amount / 100) . ' ' .  strtoupper($ch->currency) .'</td>
        </tr>

        <tr>
        <td>Description</td>
        <td>'.$ch->description.'</td>
        </tr>
        </tbody>
        </table>
        <h4>Card Details</h4>
        <table class="table table-hover">
        <tbody>
        <tr>
        <td>Card ID</td>
        <td>'.$ch->source->id.'</td>
        </tr>
        <tr>
        <td>Card Holder</td>
        <td>'.$ch->source->name.'</td>
        </tr>
        <tr>
        <td>Number</td>
        <td>•••• '.$ch->source->last4.'</td>
        </tr>
        <tr>
        <td>Fingerprint</td>
        <td>'.$ch->source->fingerprint.'</td>
        </tr>
        <tr>
        <td>Expires</td>
        <td>'. date("F", mktime(0, 0, 0, $ch->source->exp_month, 1)).' '. $ch->source->exp_year.'</td>
        </tr>
        <tr>
        <td>Type</td>
        <td>'.$ch->source->brand. ' ' . title_case($ch->source->funding) . ' Card</td>
        </tr>
        <tr>
        <td>Origin</td>
        <td>'.$ch->source->country.'</td>
        </tr>
        <tr>
        <td>CVC check</td>
        <td>'.title_case($ch->source->cvc_check).'</td>
        </tr>
        <tr>
        <td>Customer</td>
        <td><a href="https://dashboard.stripe.com/customers/'.$cus->id.'">'.$cus->id.' - '.$cus->email.'</a></td>
        </tr>
        </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <a href="https://dashboard.stripe.com/payments/'.$ch->id.'" class="btn btn-info" role="button">View More</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    </div>
    </div>');
                }


            });

            $grid->disableCreateButton();


            $grid->filter(function (Grid\Filter $filter)
            {
                $filter->disableIdFilter();

            });

            if(request()->route()->parameter('fid') != null)
            {
                $fields = SiteFormFields::where('form_id','=', request()->route()->parameter('fid'));
                if ($fields->count() > 0)
                {
                    foreach ($fields->get() as $field)
                    {
                        $grid->column($field->field_name)->display(function ($col) use ($field)
                        {
                           $data =  SiteFormData::where('submission_id','=',$this->id)->where('field_id','=',$field->id)->first();
                           if ($data)
                           {

                               if ($data->field_data != null)
                               {
                                   switch ($field->field_type)
                                   {
                                       case 'radiobutton':
                                       case 'select':
                                       case '':
                                           return $data->field_data;
                                           break;
                                       case 'file':
                                           return "<a title='Click to download' TARGET='_blank' href='".str_replace("public",env("SITE_URL"),$data->field_data)."'>Download</a>";
                                           break;
                                       case 'email':
                                           return "<a title='Click to email' href='mailto:".$data->field_data."'>".$data->field_data."</a>";
                                           break;
                                       case 'tel':
                                           return "<a title='Click to make a call' href='tel:".$data->field_data."'>".$data->field_data."</a>";
                                           break;
                                       default:
                                           return $data->field_data;
                                           break;

                                   }
                               }

                           }
                           else
                           {
                               return ;
                           }
                        });
                    }

                    $grid->filter(function (Grid\Filter $filter) use ($fields)
                    {
                        $filter->disableIdFilter();

                        foreach ($fields->get() as $field)
                        {
                            $filter->like($field->field_name);
                        }

                    });


                    $grid->column('ip')->display(function ($ip)
                    {
                        return "<a href='https://whatismyipaddress.com/ip/$ip'>$ip</a>";
                    });

                    $grid->column('ua','Info')->display(function ($info)
                    {
                        $browser = get_browser($info);
                        return $browser->browser  . ' on ' .  $browser->platform;
                    });

                    $grid->column('created_at','Time')->display(function ($created_at)
                    {
                        return Carbon::now()->format('D M,Y @ h:i:sa');
                    });
                }

            }





        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteFormSubmissions::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }


}
