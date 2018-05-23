<?php

namespace App\Admin\Controllers\Website;

use App\Admin\Databases\Website\SiteTeamPeople;

use App\Admin\Databases\Website\SiteTeams;
use App\Admin\Databases\Website\SiteTeamsMeta;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class TeamPeopleController extends Controller
{
    use ModelForm;
    private $pid;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Manage your people informations');
            $content->description('Smart Contact System');

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
        $this->pid = $id;
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit people');
            $content->description('Editing the information of the person');

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

            $content->header('Add new people');
            $content->description('Adding a new contact is simple as filling the form and submit');

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
        return Admin::grid(SiteTeamPeople::class, function (Grid $grid) {

            $grid->column('name');
            $grid->column('photo');
            $grid->column('phone');
            $grid->column('city');
            $grid->column('country');
            $grid->column('profession');

            $grid->column('team')->display(function ($team)
            {
                $ret = '';
                foreach (SiteTeamsMeta::getTeam($this->id) as $item)
                {
                    $album = SiteTeams::find($item);
                    if ($album != null)
                    {
                        $ret .= "<span style='border: 1px;padding:5px;margin: 3px; color: white; background: royalblue;'>$album->title</span>";
                    }
                }

                return $ret;
            });


            $grid->column('socials')->display(function ($social)
            {
                $ret = "";
               if ($this->fb != null)
               {
                   if (!filter_var($this->fb, FILTER_VALIDATE_URL)) {
                       $this->fb = 'https://facebook.com/' . $this->fb;
                   }

                   $ret .= '<a style="padding:5px" href="'.$this->fb .'"> <i class="fa fa-facebook"></i> </a>';
               }
                if ($this->li != null)
                {
                    if (!filter_var($this->fb, FILTER_VALIDATE_URL)) {
                        $this->li = 'https://www.linkedin.com/in/' . $this->li;
                    }

                    $ret .= '<a style="padding:5px" href="'.$this->li .'"> <i class="fa fa-linkedin" /></i> </a>';
               }
                if ($this->tt != null)
                {
                    if (!filter_var($this->tt, FILTER_VALIDATE_URL)) {
                        $this->tt = 'https://twitter.com/' . $this->tt;
                    }

                    $ret .= '<a style="padding:5px" href="'.$this->tt .'"> <i class="fa fa-twitter" /></i> </a>';
               }
                if ($this->web != null)
                {
                    $ret .= '<a style="padding:5px" href="'.$this->web .'"> <i class="fa fa-internet-explorer" /></i> </a>';
               }

               return $ret;
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
        return Admin::form(SiteTeamPeople::class, function (Form $form) {
            $countries = array(
                'AF' => 'Afghanistan',
                'AL' => 'Albania',
                'DZ' => 'Algeria',
                'AS' => 'American%20Samoa',
                'AD' => 'Andorra',
                'AO' => 'Angola',
                'AI' => 'Anguilla',
                'AQ' => 'Antarctica',
                'AG' => 'Antigua%20and%20Barbuda',
                'AR' => 'Argentina',
                'AM' => 'Armenia',
                'AW' => 'Aruba',
                'AU' => 'Australia',
                'AT' => 'Austria',
                'AZ' => 'Azerbaijan',
                'BS' => 'Bahamas',
                'BH' => 'Bahrain',
                'BD' => 'Bangladesh',
                'BB' => 'Barbados',
                'BY' => 'Belarus',
                'BE' => 'Belgium',
                'BZ' => 'Belize',
                'BJ' => 'Benin',
                'BM' => 'Bermuda',
                'BT' => 'Bhutan',
                'BO' => 'Bolivia',
                'BA' => 'Bosnia%20and%20Herzegovina',
                'BW' => 'Botswana',
                'BV' => 'Bouvet%20Island',
                'BR' => 'Brazil',
                'IO' => 'British%20Indian%20Ocean%20Territory',
                'BN' => 'Brunei',
                'BG' => 'Bulgaria',
                'BF' => 'Burkina%20Faso',
                'BI' => 'Burundi',
                'KH' => 'Cambodia',
                'CM' => 'Cameroon',
                'CA' => 'Canada',
                'CV' => 'Cape%20Verde',
                'KY' => 'Cayman%20Islands',
                'CF' => 'Central%20African%20Republic',
                'TD' => 'Chad',
                'CL' => 'Chile',
                'CN' => 'China',
                'CX' => 'Christmas%20Island',
                'CC' => 'Cocos%20%28Keeling%29%20Islands',
                'CO' => 'Colombia',
                'KM' => 'Comoros',
                'CG' => 'Congo',
                'CK' => 'Cook%20Islands',
                'CR' => 'Costa%20Rica',
                'CI' => 'C%F4te%20d%27Ivoire',
                'HR' => 'Croatia',
                'CU' => 'Cuba',
                'CW' => 'Cura%E7ao',
                'CY' => 'Cyprus',
                'CZ' => 'Czech%20Republic',
                'DK' => 'Denmark',
                'DJ' => 'Djibouti',
                'DM' => 'Dominica',
                'DO' => 'Dominican%20Republic',
                'EC' => 'Ecuador',
                'EG' => 'Egypt',
                'SV' => 'El%20Salvador',
                'GQ' => 'Equatorial%20Guinea',
                'ER' => 'Eritrea',
                'EE' => 'Estonia',
                'ET' => 'Ethiopia',
                'FK' => 'Falkland%20Islands%20%28Malvinas%29',
                'FO' => 'Faroe%20Islands',
                'FJ' => 'Fiji',
                'FI' => 'Finland',
                'FR' => 'France',
                'GF' => 'French%20Guiana',
                'PF' => 'French%20Polynesia',
                'TF' => 'French%20Southern%20Territories',
                'GA' => 'Gabon',
                'GM' => 'Gambia',
                'GE' => 'Georgia',
                'DE' => 'Germany',
                'GH' => 'Ghana',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'GL' => 'Greenland',
                'GD' => 'Grenada',
                'GP' => 'Guadeloupe',
                'GU' => 'Guam',
                'GT' => 'Guatemala',
                'GG' => 'Guernsey',
                'GN' => 'Guinea',
                'GW' => 'Guinea-Bissau',
                'GY' => 'Guyana',
                'HT' => 'Haiti',
                'HM' => 'Heard%20Island%20and%20McDonald%20Islands',
                'HN' => 'Honduras',
                'HK' => 'Hong%20Kong',
                'HU' => 'Hungary',
                'IS' => 'Iceland',
                'IN' => 'India',
                'ID' => 'Indonesia',
                'IR' => 'Iran',
                'IQ' => 'Iraq',
                'IE' => 'Ireland',
                'IM' => 'Isle%20of%20Man',
                'IL' => 'Israel',
                'IT' => 'Italy',
                'JM' => 'Jamaica',
                'JP' => 'Japan',
                'JE' => 'Jersey',
                'JO' => 'Jordan',
                'KZ' => 'Kazakhstan',
                'KE' => 'Kenya',
                'KI' => 'Kiribati',
                'KP' => 'Korea%2C%20Democratic%20People%27s%20Republic%20of',
                'KR' => 'Korea%2C%20Republic%20of',
                'KW' => 'Kuwait',
                'KG' => 'Kyrgyzstan',
                'LA' => 'Laos',
                'LV' => 'Latvia',
                'LB' => 'Lebanon',
                'LS' => 'Lesotho',
                'LR' => 'Liberia',
                'LY' => 'Libya',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'MO' => 'Macao',
                'MK' => 'Macedonia',
                'MG' => 'Madagascar',
                'MW' => 'Malawi',
                'MY' => 'Malaysia',
                'MV' => 'Maldives',
                'ML' => 'Mali',
                'MT' => 'Malta',
                'MH' => 'Marshall%20Islands',
                'MQ' => 'Martinique',
                'MR' => 'Mauritania',
                'MU' => 'Mauritius',
                'YT' => 'Mayotte',
                'MX' => 'Mexico',
                'FM' => 'Micronesia',
                'MD' => 'Moldova',
                'MC' => 'Monaco',
                'MN' => 'Mongolia',
                'ME' => 'Montenegro',
                'MS' => 'Montserrat',
                'MA' => 'Morocco',
                'MZ' => 'Mozambique',
                'MM' => 'Myanmar',
                'NA' => 'Namibia',
                'NR' => 'Nauru',
                'NP' => 'Nepal',
                'NL' => 'Netherlands',
                'NC' => 'New%20Caledonia',
                'NZ' => 'New%20Zealand',
                'NI' => 'Nicaragua',
                'NE' => 'Niger',
                'NG' => 'Nigeria',
                'NU' => 'Niue',
                'NF' => 'Norfolk%20Island',
                'MP' => 'Northern%20Mariana%20Islands',
                'NO' => 'Norway',
                'OM' => 'Oman',
                'PK' => 'Pakistan',
                'PW' => 'Palau',
                'PS' => 'Palestinian%20Territory%2C%20Occupied',
                'PA' => 'Panama',
                'PG' => 'Papua%20New%20Guinea',
                'PY' => 'Paraguay',
                'PE' => 'Peru',
                'PH' => 'Philippines',
                'PN' => 'Pitcairn',
                'PL' => 'Poland',
                'PT' => 'Portugal',
                'PR' => 'Puerto%20Rico',
                'QA' => 'Qatar',
                'RE' => 'R%E9union',
                'RO' => 'Romania',
                'RU' => 'Russian%20Federation',
                'RW' => 'Rwanda',
                'BL' => 'Saint%20Barth%E9lemy',
                'SH' => 'Saint%20Helena%2C%20Ascension%20and%20Tristan%20da%20Cunha',
                'KN' => 'Saint%20Kitts%20and%20Nevis',
                'LC' => 'Saint%20Lucia',
                'MF' => 'Saint%20Martin%20%28French%20part%29',
                'PM' => 'Saint%20Pierre%20and%20Miquelon',
                'VC' => 'Saint%20Vincent%20and%20the%20Grenadines',
                'WS' => 'Samoa',
                'SM' => 'San%20Marino',
                'ST' => 'Sao%20Tome%20and%20Principe',
                'SA' => 'Saudi%20Arabia',
                'SN' => 'Senegal',
                'RS' => 'Serbia',
                'SC' => 'Seychelles',
                'SL' => 'Sierra%20Leone',
                'SG' => 'Singapore',
                'SX' => 'Sint%20Maarten%20%28Dutch%20part%29',
                'SK' => 'Slovakia',
                'SI' => 'Slovenia',
                'SB' => 'Solomon%20Islands',
                'SO' => 'Somalia',
                'ZA' => 'South%20Africa',
                'GS' => 'South%20Georgia%20and%20the%20South%20Sandwich%20Islands',
                'SS' => 'South%20Sudan',
                'ES' => 'Spain',
                'LK' => 'Sri%20Lanka',
                'SD' => 'Sudan',
                'SR' => 'Suriname',
                'SJ' => 'Svalbard%20and%20Jan%20Mayen',
                'SZ' => 'Swaziland',
                'SE' => 'Sweden',
                'CH' => 'Switzerland',
                'SY' => 'Syrian%20Arab%20Republic',
                'TW' => 'Taiwan',
                'TJ' => 'Tajikistan',
                'TZ' => 'Tanzania',
                'TH' => 'Thailand',
                'TL' => 'Timor-Leste',
                'TG' => 'Togo',
                'TK' => 'Tokelau',
                'TO' => 'Tonga',
                'TT' => 'Trinidad%20and%20Tobago',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
                'TM' => 'Turkmenistan',
                'TV' => 'Tuvalu',
                'UG' => 'Uganda',
                'UA' => 'Ukraine',
                'AE' => 'United%20Arab%20Emirates',
                'GB' => 'United%20Kingdom',
                'US' => 'United%20States',
                'UY' => 'Uruguay',
                'UZ' => 'Uzbekistan',
                'VU' => 'Vanuatu',
                'VE' => 'Venezuela',
                'VN' => 'Vietnam',
                'VG' => 'Virgin%20Islands%2C%20British',
                'VI' => 'Virgin%20Islands%2C%20U.S.',
                'EH' => 'Western%20Sahara',
                'YE' => 'Yemen',
                'ZM' => 'Zambia',
                'ZW' => 'Zimbabwe',);
            $form->image('photo')->rules('required');
            $form->text('name')->placeholder('John dev')->rules('required');
            $form->mobile('phone')->options(['mask' => '+99 999 9999 9999'])->rules('required');
            $form->email('email')->placeholder('example@example.com')->rules('required');
            $form->multipleSelect('teams')->options(SiteTeams::allNodes())->value(SiteTeamsMeta::getTeam($this->pid));
            $form->ignore('teams');
            $form->text('city')->rules('required');
            $form->select('country')->options($countries)->rules('required');
            $form->date('dob')->rules('required');
            $form->text('profession')->rules('required');
            $form->divider();
            $form->text('fb','Facebook');
            $form->text('li','LinkedIn');
            $form->text('tt','Twitter');
            $form->text('web');
            $form->saved(function (Form $form)
            {
                SiteTeamsMeta::where('people_id','=',$form->model()->id)->delete();
                foreach (request('teams') as $item)
                {
                    if ($item != null)
                    {
                        $meta = new SiteTeamsMeta();
                        $meta->people_id = $form->model()->id;
                        $meta->teams_id = $item;
                        $meta->save();
                    }
                }
            });


        });
    }
}
