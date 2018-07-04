<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteFormDelegates;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use function foo\func;
use Illuminate\Support\Facades\Storage;

class DelegatesFormController extends Controller
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

            $content->header('Delegates Applicants');
            $content->description('See the submissions and reply them from here');

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

            $content->header('Reply to the applicant');
            $content->description('Respond to the application sent by the applicant');

            $content->body($this->form()->edit($id));
        });
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
        return Admin::grid(SiteFormDelegates::class, function (Grid $grid) {
            $countries = array("Afghanistan","Albania","Algeria","American Samoa","Andorra","Angola","Anguilla","Antarctica","Antigua and Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia and Herzegovina","Botswana","Bouvet Island","Brazil","British Indian Ocean Territory","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central African Republic","Chad","Chile","China","Christmas Island","Cocos (Keeling) Islands","Colombia","Comoros","Congo","Cook Islands","Costa Rica","Côte d'Ivoire","Croatia","Cuba","Curaçao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands (Malvinas)","Faroe Islands","Fiji","Finland","France","French Guiana","French Polynesia","French Southern Territories","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guadeloupe","Guam","Guatemala","Guernsey","Guinea","Guinea-Bissau","Guyana","Haiti","Heard Island and McDonald Islands","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Korea, Democratic People's Republic of","Korea, Republic of","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macao","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Martinique","Mauritania","Mauritius","Mayotte","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauru","Nepal","Netherlands","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Niue","Norfolk Island","Northern Mariana Islands","Norway","Oman","Pakistan","Palau","Palestinian Territory, Occupied","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Pitcairn","Poland","Portugal","Puerto Rico","Qatar","Réunion","Romania","Russian Federation","Rwanda","Saint Barthélemy","Saint Helena, Ascension and Tristan da Cunha","Saint Kitts and Nevis","Saint Lucia","Saint Martin (French part)","Saint Pierre and Miquelon","Saint Vincent and the Grenadines","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Sint Maarten (Dutch part)","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Georgia and the South Sandwich Islands","South Sudan","Spain","Sri Lanka","Sudan","Suriname","Svalbard and Jan Mayen","Swaziland","Sweden","Switzerland","Syrian Arab Republic","Taiwan","Tajikistan","Tanzania","Thailand","Timor-Leste","Togo","Tokelau","Tonga","Trinidad and Tobago","Tunisia","Turkey","Turkmenistan","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States","Uruguay","Uzbekistan","Vanuatu","Venezuela","Vietnam","Virgin Islands, British","Virgin Islands, U.S.","Western Sahara","Yemen","Zambia","Zimbabwe");
            $tmpCountry = array();
            foreach ($countries as $country)
            {
                $tmpCountry[str_slug($country)] = $country;
            }
            $countries = $tmpCountry;

            $grid->model()->orderBy('created_at','desc');
            $grid->column('title')->sortable();
            $grid->column('preferred_name')->display(function ($name)
            {
                if ($this->seen == 0)
                {
                    return "<a href='".route('delegates.edit',['contact'=>$this->id])."' class='text-primary' title='Not yet replied'>$name</a>";
                }
                else
                {
                    return $name;
                }
            })->sortable();

            $grid->columns(array(
                'passport'=>'Passport #',

            ));
            $grid->column('nationality')->display(function ($nationality) use ($countries)
            {

                try
                {
                    $tmpC = $countries[$nationality];
                }
                catch (\Exception $exception)
                {
                    $tmpC = "";
                }


                return urldecode($tmpC);
            })->sortable();
            $grid->columns(array(
                'city'=>'City',
                'occupation'=>'Occupation',
                'university'=>'University',
                'company'=>'Company',
                'ministry'=>'Ministry',
                'email'=>'Email',
                'mob'=>'Mobile',
                ));
            $grid->column('pitching_deck')->display(function($portfolio)
            {
                if($portfolio != null){
                    return "<a href='".Storage::disk('site_upload')->url('files/pdfs/'.$portfolio)."'>Download</a>";
                }
              return '';
            });

            $grid->column('created_at','Time')->sortable()->style('width:150px');
            $grid->actions(function (Grid\Displayers\Actions $actions)
            {
                $actions->disableEdit();
                $actions->prepend('<a href="'.route('delegates.edit',['contact'=>$actions->getKey()]).'"><i class="fa fa-reply"></i></a>');
            });
            $grid->disableCreateButton();

            $grid->filter(function($filter)use ($countries){

                // Remove the default id filter
                $filter->disableIdFilter();

                // Add a column filter
                $filter->in('nationality', 'Country')->multipleSelect($countries);
                $filter->like('title');
                $filter->like('preferred_name');
                $filter->like('city');
                $filter->like('passport');
                $filter->like('occupation');
                $filter->like('university');
                $filter->like('ministry');
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SiteFormDelegates::class, function (Form $form) {

            $form->ckeditor('reply_msg','Your message')->placeholder('Send a reply message to the user');
            $form->ignore('reply_msg');
            $form->divider();
            $form->html('<button type="submit" class="btn btn-info pull-right" data-loading-text="<i class=\'fa fa-spinner fa-spin \'></i> Save"> <span class="fa fa-reply"></span> Reply</button>');


            $form->display('title');
            $form->display('first_name');
            $form->display('last_name');
            $form->display('preferred_name');
            $form->display('passport');
            $form->display('nationality');
            $form->display('city');
            $form->display('occupation');
            $form->display('university');
            $form->display('company');
            $form->display('ministry');
            $form->display('preferred_name');
            $form->display('email');
            $form->display('mob','Mobile');

            $form->display('facebook');
            $form->display('linkedin');
            $form->display('scholarhub');

            $form->display('purpose');
            $form->display('city_message','Message');
            $form->display('track_conference');
            $form->display('chapter_referral');

            $form->display('referred_person');
            $form->display('fcs_package');
            $form->display('scholarship');
            $form->display('newsletter_subscription');
            $form->display('created_at', 'Sent at');



            $form->disableReset();
            $form->disableSubmit();


            $form->saving(function (Form $form)
            {
                if (request('reply_msg') != null || empty(request('reply_msg')) == false)
                {
                    $data = ['email' => $form->model()->email,'name'=> $form->model()->preferred_name,'msg'=>$form->model()->city_message, 'reply_msg'=> request('reply_msg')];
                    $form->model()->seen = 1;
                    Mail::send('mails.mailTmp1', $data, function ($m) use ($data) {
                        $m->from('community@futurecitysummit.org', 'Future City Summit');
                        $m->replyTo('community@futurecitysummit.org', 'Future City Summit');
                        $m->to($data['email'], $data['name'])->subject('Delegates application form submit reply | Future City Summit');
                    });
                }

            });
        });
    }
}
