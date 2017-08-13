<?php

/**
 * Created by PhpStorm.
 * User: Russell
 * Date: 04/01/2015
 * Time: 12:36
 */
class LinkChecker
{

    private $sectionTitle;
    private $pageForLinkChecker;

    public function __construct()
    {

        $this->sectionTitle = "Link Checker";
        $this->pageForLinkChecker = array(
            "1" => array(
                array("africa", "africa"),
                array("africa", "algeria"),
                array("africa", "angola")
            ),
            "2" => array(
                array("africa", "benin"),
                array("africa", "botswana"),
                array("africa", "burkina_faso"),
                array("africa", "burundi")
            ),
            "3" => array(
                array("africa", "cameroon"),
                array("africa", "cape_verde"),
                array("africa", "central_african_republic"),
                array("africa", "chad"),
                array("africa", "comoros"),
                array("africa", "ivory_coast")
            )
        ,
            "4" => array(array("africa", "congo"), array("africa", "djibouti"))
        ,
            "5" => array(
                array("africa", "equatorial_guinea"),
                array("africa", "eritrea"),
                array("africa", "ethiopia"),
                array("africa", "egypt")
            )

        ,
            "6" => array(
                array("africa", "gabon"),
                array("africa", "gambia"),
                array("africa", "ghana"),
                array("africa", "guinea"),
                array("africa", "guinea_bissau")
            )

        ,
            "7" => array(
                array("africa", "kenya"),
                array("africa", "lesotho"),
                array("africa", "liberia"),
                array("africa", "libya")
            )

        ,
            "8" => array(
                array("africa", "madagascar"),
                array("africa", "malawi"),
                array("africa", "mali"),
                array("africa", "mauritius"),
                array("africa", "morocco"),
                array("africa", "mozambique"),
                array("africa", "mauritania")
            )

        ,
            "9" => array(
                array("africa", "namibia"),
                array("africa", "niger"),
                array("africa", "nigeria"),
                array("africa", "north_sudan")
            )

        ,
            "10" => array(
                array("africa", "republic of congo"),
                array("africa", "reunion"),
                array("africa", "rwanda")
            )

        ,
            "11" => array(
                array("africa", "sao_tome_principe"),
                array("africa", "senegal"),
                array("africa", "seychelles"),
                array("africa", "sierra_leone"),
                array("africa", "somali_republic"),
                array("africa", "south_sudan"),
                array("africa", "swaziland")
            )

        ,
            "12" => array(
                array("africa", "south_africa"),
                array("africa", "south_africa_eastern_cape"),
                array("africa", "south_africa_free_state"),
                array("africa", "south_africa_gauteng"),
                array("africa", "south_africa_kwazulu-natal")
            )

        ,
            "13" => array(
                array("africa", "south_africa_limpopo"),
                array("africa", "south_africa_mpumalanga"),
                array("africa", "south_africa_northern_cape"),
                array("africa", "south_africa_north-west_province"),
                array("africa", "south_africa_western_cape")
            )

        ,
            "14" => array(
                array("africa", "tanzania"),
                array("africa", "togo"),
                array("africa", "tunisia")
            )

        ,
            "15" => array(
                array("africa", "uganda"),
                array("africa", "western_sahara"),
                array("africa", "zambia"),
                array("africa", "zimbabwe")
            )

        ,
            "16" => array(
                array("asia", "asia"),
                array("asia", "afghanistan"),
                array("asia", "bangladesh"),
                array("asia", "bhutan"),
                array("asia", "brunei"),
                array("asia", "cambodia")
            )

        ,
            "17" => array(
                array("asia", "china"),
                array("asia", "china_anhui"),
                array("asia", "china_beijing"),
                array("asia", "china_chongqing"),
                array("asia", "china_fujian")
            )

        ,
            "18" => array(
                array("asia", "china_gansu"),
                array("asia", "china_guangdong"),
                array("asia", "china_guangxi"),
                array("asia", "china_guizhou")
            )

        ,
            "19" => array(
                array("asia", "china_hainan"),
                array("asia", "china_hebei"),
                array("asia", "china_heilongjiang"),
                array("asia", "china_henan"),
                array("asia", "hong_kong"),
                array("asia", "china_hubei"),
                array("asia", "china_hunan")
            )

        ,
            "20" => array(
                array("asia", "china_jiangsu"),
                array("asia", "china_jiangxi"),
                array("asia", "china_jilin"),
                array("asia", "china_liaoning"),
                array("asia", "china_neimenggu"),
                array("asia", "china_ningxia")
            )

        ,
            "21" => array(
                array("asia", "china_qinghai"),
                array("asia", "china_shaanxi"),
                array("asia", "china_shandong"),
                array("asia", "china_shanghai"),
                array("asia", "china_shanxi"),
                array("asia", "china_sichuan")
            )

        ,
            "22" => array(
                array("asia", "china_tianjin"),
                array("asia", "china_xinjiang"),
                array("asia", "china_xizang"),
                array("asia", "china_yunnan"),
                array("asia", "china_zhejiang")
            )

        ,
            "23" => array(
                array("asia", "india"),
                array("asia", "india_andaman_and_nicobar"),
                array("asia", "india_andhra_pradesh"),
                array("asia", "india_arunachal_pradesh"),
                array("asia", "india_assam"),
                array("asia", "india_bihar")
            )

        ,
            "24" => array(
                array("asia", "india_chhattisgarh"),
                array("asia", "india_delhi"),
                array("asia", "india_goa"),
                array("asia", "india_gujarat"),
                array("asia", "india_haryana"),
                array("asia", "india_himachal_pradesh")
            )

        ,
            "25" => array(
                array("asia", "india_jammu_and_kasmir"),
                array("asia", "india_jharkhand"),
                array("asia", "india_karnataka"),
                array("asia", "india_kerala"),
                array("asia", "india_lakshadweep")
            )

        ,
            "26" => array(
                array("asia", "india_madhya_pradesh"),
                array("asia", "india_maharashtra"),
                array("asia", "india_manipur"),
                array("asia", "india_meghalaya"),
                array("asia", "india_mizoram")
            )

        ,
            "27" => array(
                array("asia", "india_nagaland"),
                array("asia", "india_orissa"),
                array("asia", "india_punjab"),
                array("asia", "india_rajasthan"),
                array("asia", "india_sikkim")
            )

        ,
            "28" => array(
                array("asia", "india_tamil_nadu"),
                array("asia", "india_tripura"),
                array("asia", "india_uttar_pradesh"),
                array("asia", "india_uttarakhand"),
                array("asia", "india_west_bengal")
            )

        ,
            "29" => array(
                array("asia", "indonesia"),
                array("asia", "indonesia_bali"),
                array("asia", "indonesia_irian"),
                array("asia", "indonesia_java")
            )

        ,
            "30" => array(
                array("asia", "indonesia_kalimantan_barat"),
                array("asia", "indonesia_kalimantan_selatan"),
                array("asia", "indonesia_kalimantan_tengah"),
                array("asia", "indonesia_kalimantan_timur")
            )

        ,
            "31" => array(
                array("asia", "indonesia_maluku"),
                array("asia", "indonesia_nusa_tenggara_barat"),
                array("asia", "indonesia_sulawesi"),
                array("asia", "indonesia_sumatra")
            )

        ,
            "32" => array(
                array("asia", "japan"),
                array("asia", "kazakhstan"),
                array("asia", "kyrgyzstan"),
                array("asia", "laos")
            )

        ,
            "33" => array(
                array("asia", "malaysia"),
                array("asia", "malaysia_johor"),
                array("asia", "malaysia_kedah"),
                array("asia", "malaysia_kelantan"),
                array("asia", "malaysia_kuala_lumpur")
            )

        ,
            "34" => array(
                array("asia", "malaysia_melaka"),
                array("asia", "malaysia_negeri_sembilan"),
                array("asia", "malaysia_pahang"),
                array("asia", "malaysia_penang"),
                array("asia", "malaysia_perak"),
                array("asia", "malaysia_perlis")
            )

        ,
            "35" => array(
                array("asia", "malaysia_sabah"),
                array("asia", "malaysia_sarawak"),
                array("asia", "malaysia_selangor"),
                array("asia", "malaysia_terengganu")
            )

        ,
            "36" => array(
                array("asia", "maldives"),
                array("asia", "mongolia"),
                array("asia", "myanmar"),
                array("asia", "nepal"),
                array("asia", "north_korea")
            )

        ,
            "37" => array(
                array("asia", "pakistan"),
                array("asia", "philippines"),
                array("asia", "singapore"),
                array("asia", "south_korea"),
                array("asia", "sri_lanka")
            )

        ,
            "38" => array(
                array("asia", "taiwan"),
                array("asia", "tajikistan"),
                array("asia", "thailand"),
                array("asia", "timor_leste"),
                array("asia", "turkmenistan"),
                array("asia", "uzbekistan"),
                array("asia", "vietnam")
            )

        ,
            "39" => array(
                array("australasia", "australasia"),
                array("australasia", "australia"),
                array("australasia", "australia_christmas_island"),
                array("australasia", "australia_cocos_(keeling)_islands"),
                array("australasia", "australia_new_south_wales"),
                array("australasia", "australia_northern_territory")
            )

        ,
            "40" => array(
                array("australasia", "australia_queensland"),
                array("australasia", "australia_south_australia"),
                array("australasia", "australia_tasmania"),
                array("australasia", "australia_victoria"),
                array("australasia", "australia_western_australia")
            )

        ,
            "41" => array(
                array("australasia", "american_samoa"),
                array("australasia", "cook_islands"),
                array("australasia", "coral_sea_islands"),
                array("australasia", "federated_states_of_micronesia"),
                array("australasia", "fiji"),
                array("australasia", "french_polynesia")
            )

        ,
            "42" => array(
                array("australasia", "guam"),
                array("australasia", "kiribati"),
                array("australasia", "marshall_islands")
            )

        ,
            "43" => array(
                array("australasia", "nauru"),
                array("australasia", "new_caledonia"),
                array("australasia", "new_zealand"),
                array("australasia", "niue"),
                array("australasia", "norfolk_island"),
                array("australasia", "northern_mariana_islands")
            )

        ,
            "44" => array(
                array("australasia", "palau"),
                array("australasia", "papua_new_guinea"),
                array("australasia", "pitcairn_islands", "solomon_islands"),
                array("australasia", "new_zealand_stewart_island")
            )

        ,
            "45" => array(
                array("australasia", "tokelau"),
                array("australasia", "tonga"),
                array("australasia", "tuvalu"),
                array("australasia", "vanuatu"),
                array("australasia", "wallis_and_fortuna_islands"),
                array("australasia", "western_samoa")
            )

        ,
            "46" => array(
                array("europe", "albania"),
                array("europe", "andorra"),
                array("europe", "armenia"),
                array("europe", "austria"),
                array("europe", "azerbaijan")
            )

        ,
            "47" => array(
                array("europe", "belgium"),
                array("europe", "belarus"),
                array("europe", "bosnia"),
                array("europe", "bulgaria")
            )

        ,
            "48" => array(
                array("europe", "croatia"),
                array("europe", "cyprus"),
                array("europe", "czech_republic"),
                array("europe", "denmark"),
                array("europe", "estonia"),
                array("europe", "denmark_faeroe_islands"),
                array("europe", "finland")
            )

        ,
            "49" => array(
                array("europe", "france"),
                array("europe", "france_auvergne_rhone_alpes"),
                array("europe", "france_bourgogne_franche_comte"),
                array("europe", "france_bretagne")
            )

        ,
            "50" => array(
                array("europe", "france_centre"),
                array("europe", "france_corse"),
                array("europe", "france_grand_est"),
                array("europe", "france_hauts_de_france")
            )

        ,
            "51" => array(
                array("europe", "france_ile_de_France"),
                array("europe", "france_normandie"),
                array("europe", "france_nouvelle_aquitaine")
            )

        ,
            "52" => array(
                array("europe", "france_occitanie"),
                array("europe", "france_pays_de_la_loire"),
                array("europe", "france_provence_alpes_cote_dazur")
            )

        ,
            "53" => array(
                array("europe", "germany"),
                array("europe", "germany_bavaria"),
                array("europe", "germany_berlin"),
                array("europe", "germany_baden_wuerttemberg"),
                array("europe", "germany_brandenburg"),
                array("europe", "germany_bremen")
            )

        ,
            "54" => array(
                array("europe", "germany_hamburg"),
                array("europe", "germany_hesse"),
                array("europe", "germany_lower_saxony"),
                array("europe", "germany_mecklenburg-western_pomerania"),
                array("europe", "germany_north_rhine-westphalia"),
                array("europe", "germany_rhineland-palatinate")
            )

        ,
            "55" => array(
                array("europe", "germany_saarland"),
                array("europe", "germany_saxony"),
                array("europe", "germany_saxony-anhalt"),
                array("europe", "germany_schleswig-holstein"),
                array("europe", "germany_thuringia")
            )

        ,
            "56" => array(
                array("europe", "georgia"),
                array("europe", "gibraltar"),
                array("europe", "denmark_greenland"),
                array("europe", "hungary"),
                array("europe", "iceland ")
            )

        ,
            "57" => array(
                array("europe", "greece"),
                array("europe", "greece_corfu"),
                array("europe", "greece_crete"),
                array("europe", "greece_lesvos_migration_island"),
                array("europe", "greece_rhodes")
            )

        ,
            "58" => array(
                array("europe", "ireland"),
                array("europe", "ireland_carlow"),
                array("europe", "ireland_cavan"),
                array("europe", "ireland_clare"),
                array("europe", "ireland_cork")
            )

        ,
            "59" => array(
                array("europe", "ireland_donegal"),
                array("europe", "ireland_dublin"),
                array("europe", "ireland_galway"),
                array("europe", "ireland_kerry"),
                array("europe", "ireland_kildare"),
                array("europe", "ireland_kilkenny")
            )

        ,
            "60" => array(
                array("europe", "ireland_laoighis"),
                array("europe", "ireland_leitrim"),
                array("europe", "ireland_limerick"),
                array("europe", "ireland_longford"),
                array("europe", "ireland_louth")
            )

        ,
            "61" => array(
                array("europe", "ireland_mayo"),
                array("europe", "ireland_meath"),
                array("europe", "ireland_monaghan"),
                array("europe", "ireland_offaly"),
                array("europe", "ireland_roscommon")
            )

        ,
            "62" => array(
                array("europe", "ireland_sligo"),
                array("europe", "ireland_tipperary"),
                array("europe", "ireland_waterford"),
                array("europe", "ireland_westmeath"),
                array("europe", "ireland_wexford"),
                array("europe", "ireland_wicklow")
            )

        ,
            "63" => array(
                array("europe", "italy"),
                array("europe", "italy_sardinia"),
                array("europe", "italy_sicily")
            )

        ,
            "64" => array(
                array("europe", "kosovo"),
                array("europe", "latvia"),
                array("europe", "lichtenstein"),
                array("europe", "lithuania"),
                array("europe", "luxemburg")
            )

        ,
            "65" => array(
                array("europe", "macedonia"),
                array("europe", "malta"),
                array("europe", "moldova"),
                array("europe", "monaco"),
                array("europe", "montenegro"),
                array("europe", "montenegro_lake_skadar ")
            )

        ,
            "66" => array(
                array("europe", "netherlands"),
                array("europe", "norway"),
                array("europe", "poland"),
                array("europe", "romania")
            )

        ,
            "67" => array(
                array("europe", "portugal"),
                array("europe", "portugal_alentejo"),
                array("europe", "portugal_algarve"),
                array("europe", "portugal_azores"),
                array("europe", "portugal_centro"),
                array("europe", "portugal_lisboa_e_vale_do_tejo"),
                array("europe", "portugal_madeira"),
                array("europe", "portugal_norte")
            )

        ,
            "68" => array(
                array("europe", "russia"),
                array("europe", "russia_altai"),
                array("europe", "russia_baikal"),
                array("europe", "russia_caucasus"),
                array("europe", "russia_central_russia")
            )

        ,
            "69" => array(
                array("europe", "russia_eastern_siberia"),
                array("europe", "russia_far_east"),
                array("europe", "russia_kamchatka"),
                array("europe", "russia_northwest_russia")
            )

        ,
            "70" => array(
                array("europe", "russia_russian_arctic"),
                array("europe", "russia_south_russia"),
                array("europe", "russia_urals"),
                array("europe", "russia_western_siberia")
            )

        ,
            "71" => array(
                array("europe", "san_marino"),
                array("europe", "serbia"),
                array("europe", "slovakia"),
                array("europe", "slovenia"),
                array("europe", "sweden"),
                array("europe", "switzerland")
            )

        ,
            "72" => array(
                array("europe", "spain"),
                array("europe", "spain_andalucia"),
                array("europe", "spain_aragon"),
                array("europe", "spain_asturias"),
                array("europe", "spain_balearic_sslands")
            )

        ,
            "73" => array(
                array("europe", "spain_Canary Islands"),
                array("europe", "spain_cantabria"),
                array("europe", "spain_castilla-la_mancha"),
                array("europe", "spain_castilla-leon"),
                array("europe", "spain_cataluna")
            )

        ,
            "74" => array(
                array("europe", "spain_extremadura"),
                array("europe", "spain_galicia"),
                array("europe", "spain_madrid"),
                array("europe", "spain_murcia")
            )

        ,
            "75" => array(
                array("europe", "spain_navarra"),
                array("europe", "spain_pais_vasco"),
                array("europe", "spain_rioja"),
                array("europe", "spain_valencia")
            )

        ,
            "76" => array(array("europe", "turkey"), array("europe", "ukraine"))
        ,
            "77" => array(
                array("europe", "united_kingdom_general"),
                array("europe", "united_kingdom_channel_islands"),
                array("europe", "england_general"),
                array("europe", "united_kingdom_isle_of_man"),
                array("europe", "northern_ireland"),
                array("europe", "scotland_general"),
                array("europe", "wales_general")
            )
        ,
            "78" => array(
                array("europe", "england_avon_and_bristol"),
                array("europe", "england_berkshire"),
                array("europe", "england_bedfordshire"),
                array("europe", "england_buckinghamshire")
            )
        ,
            "79" => array(
                array("europe", "england_cambridgeshire"),
                array("europe", "england_cheshire"),
                array("europe", "england_cleveland"),
                array("europe", "england_cornwall"),
                array("europe", "england_cumbria")
            )
        ,
            "80" => array(
                array("europe", "england_derbyshire"),
                array("europe", "england_devon"),
                array("europe", "england_dorset"),
                array("europe", "england_durham"),
                array("europe", "england_essex")
            )
        ,
            "81" => array(
                array("europe", "england_gloucestershire"),
                array("europe", "england_greater_london"),
                array("europe", "england_greater_manchester"),
                array("europe", "england_hampshire"),
                array("europe", "england_herefordshire"),
                array("europe", "england_hertfordshire")
            )
        ,
            "82" => array(
                array("europe", "england_isle_of_wight"),
                array("europe", "england_kent"),
                array("europe", "england_lancashire"),
                array("europe", "england_leicestershire"),
                array("europe", "england_lincolnshire")
            )
        ,
            "83" => array(
                array("europe", "england_merseyside"),
                array("europe", "england_norfolk"),
                array("europe", "england_northamptonshire"),
                array("europe", "england_northumberland"),
                array("europe", "england_nottinghamshire"),
                array("europe", "england_oxfordshire")
            )
        ,
            "84" => array(
                array("europe", "england_scilly_isles"),
                array("europe", "england_shropshire"),
                array("europe", "england_somerset"),
                array("europe", "england_staffordshire"),
                array("europe", "england_suffolk"),
                array("europe", "england_surrey"),
                array("europe", "england_east_sussex"),
                array("europe", "england_west_sussex")
            )
        ,
            "85" => array(
                array("europe", "england_tyne_and_wear"),
                array("europe", "england_warwickshire"),
                array("europe", "england_west_midlands"),
                array("europe", "england_wiltshire"),
                array("europe", "england_worcestershire")
            )
        ,
            "86" => array(
                array("europe", "england_east_yorkshire"),
                array("europe", "england_north_yorkshire"),
                array("europe", "england_south_yorkshire)"),
                array("europe", "england_west_yorkshire")
            )
        ,
            "87" => array(
                array("europe", "northern_ireland_antrim"),
                array("europe", "northern_ireland_armagh"),
                array("europe", "northern_ireland_down"),
                array("europe", "northern_ireland_fermanagh"),
                array("europe", "northern_ireland_londonderry"),
                array("europe", "northern_ireland_tyrone")
            )
        ,
            "88" => array(
                array("europe", "scotland_aberdeenshire"),
                array("europe", "scotland_angus"),
                array("europe", "scotland_argyll_and_bute")
            )
        ,
            "89" => array(
                array("europe", "scotland_city_of_aberdeen"),
                array("europe", "scotland_city_of_dundee"),
                array("europe", "scotland_city_of_edinburgh"),
                array("europe", "scotland_city_of_glasgow"),
                array("europe", "scotland_clackmannanshire")
            )
        ,
            "90" => array(
                array("europe", "scotland_dumfries_and_galloway"),
                array("europe", "scotland_east_ayrshire"),
                array("europe", "scotland_east_dunbartonshire"),
                array("europe", "scotland_east_lothian"),
                array("europe", "scotland_east_renfrewshire")
            )
        ,
            "91" => array(
                array("europe", "scotland_falkirk"),
                array("europe", "scotland_fife"),
                array("europe", "scotland_highlands"),
                array("europe", "scotland_inverclyde"),
                array("europe", "scotland_midlothian"),
                array("europe", "scotland_moray")
            )
        ,
            "92" => array(
                array("europe", "scotland_north_ayrshire"),
                array("europe", "scotland_north_lanarkshire"),
                array("europe", "scotland_orkney"),
                array("europe", "scotland_perth_and_kinross"),
                array("europe", "scotland_renfrewshire")
            )
        ,
            "93" => array(
                array("europe", "scotland_scottish_borders"),
                array("europe", "scotland_shetland"),
                array("europe", "scotland_south_ayrshire"),
                array("europe", "scotland_south_lanarkshire"),
                array("europe", "scotland_stirling")
            )
        ,
            "94" => array(
                array("europe", "scotland_west_dunbartonshire"),
                array("europe", "scotland_west_lothian"),
                array("europe", "scotland_western_isles")
            )
        ,
            "95" => array(
                array("europe", "wales_anglesey"),
                array("europe", "wales_blaenau_gwent_county_borough"),
                array("europe", "wales_bridgend")
            )
        ,
            "96" => array(
                array("europe", "wales_caerphilly_county_borough"),
                array("europe", "wales_cardiff_city"),
                array("europe", "wales_carmarthenshire"),
                array("europe", "wales_ceredigion"),
                array("europe", "wales_conwy")
            )
        ,
            "97" => array(
                array("europe", "wales_denbighshire"),
                array("europe", "wales_flintshire"),
                array("europe", "wales_gwynedd"),
                array("europe", "wales_merthyr_tydfil_county_borough"),
                array("europe", "wales_monmouthshire")
            )
        ,
            "98" => array(
                array("europe", "wales_neath_port_talbot_county_borough"),
                array("europe", "wales_newport_city"),
                array("europe", "wales_pembrokeshire"),
                array("europe", "wales_powys")
            )
        ,
            "99" => array(
                array("europe", "wales_rhondda_cynon_taf_county_borough"),
                array("europe", "wales_swansea_city"),
                array("europe", "wales_torfaen_county_borough"),
                array("europe", "wales_vale_of_glamorgan_county_borough"),
                array("europe", "wales_wrexham_county_borough")
            )
        ,
            "100" => array(
                array("middle_east", "middle_east"),
                array("middle_east", "armenia"),
                array("middle_east", "bahrain"),
                array("middle_east", "iran"),
                array("middle_east", "iraq"),
                array("middle_east", "israel")
            )
        ,
            "101" => array(
                array("middle_east", "jordan"),
                array("middle_east", "kuwait"),
                array("middle_east", "lebanon"),
                array("middle_east", "oman"),
                array("middle_east", "palestine")
            )
        ,
            "102" => array(
                array("middle_east", "qatar"),
                array("middle_east", "saudi_arabia"),
                array("middle_east", "syria"),
                array("middle_east", "uae"),
                array("middle_east", "yemen")
            )
        ,
            "103" => array(
                array("n_america", "bermuda"),
                array("n_america", "canada"),
                array("n_america", "mexico"),
                array("n_america", "united_states"),
                array("n_america", "st_pierre_et_miquelon")
            )
        ,
            "104" => array(
                array("n_america", "alberta"),
                array("n_america", "british_columbia"),
                array("n_america", "manitoba")
            )
        ,
            "105" => array(
                array("n_america", "new_brunswick"),
                array("n_america", "newfoundland"),
                array("n_america", "nw_territory"),
                array("n_america", "nova_scotia"),
                array("n_america", "nunavut")
            )
        ,
            "106" => array(
                array("n_america", "ontario"),
                array("n_america", "prince_edward_island"),
                array("n_america", "quebec"),
                array("n_america", "saskatchewan"),
                array("n_america", "yukon")
            )
        ,
            "107" => array(
                array("n_america", "mexico_aguascalientes"),
                array("n_america", "mexico_baja_california"),
                array("n_america", "mexico_baja_california_sur")
            )
        ,
            "108" => array(
                array("n_america", "mexico_campeche"),
                array("n_america", "mexico_chiapas"),
                array("n_america", "mexico_chihuahua"),
                array("n_america", "mexico_coahuila"),
                array("n_america", "mexico_colima")
            )
        ,
            "109" => array(
                array("n_america", "mexico_durango"),
                array("n_america", "mexico_guanajuato"),
                array("n_america", "mexico_guerrero"),
                array("n_america", "mexico_hidalgo"),
                array("n_america", "mexico_jalisco")
            )
        ,
            "110" => array(
                array("n_america", "mexico_mexico"),
                array("n_america", "mexico_michoacan"),
                array("n_america", "mexico_morelos"),
                array("n_america", "mexico_nayarit"),
                array("n_america", "mexico_nuevo_leon")
            )
        ,
            "111" => array(
                array("n_america", "mexico_oaxaca"),
                array("n_america", "mexico_puebla"),
                array("n_america", "mexico_queretaro"),
                array("n_america", "mexico_quintana_roo")
            )
        ,
            "112" => array(
                array("n_america", "mexico_san_luis_potosi"),
                array("n_america", "mexico_sinaloa"),
                array("n_america", "mexico_sonora"),
                array("n_america", "mexico_tabasco"),
                array("n_america", "mexico_tamaulipas"),
                array("n_america", "mexico_tlaxcala ")
            )
        ,
            "113" => array(
                array("n_america", "mexico_veracruz"),
                array("n_america", "mexico_yucatan"),
                array("n_america", "mexico_zacatecas"),
                array("n_america", "mexico_districto_federal")
            )
        ,
            "114" => array(
                array("n_america", "alabama"),
                array("n_america", "alaska"),
                array("n_america", "arizona"),
                array("n_america", "arkansas")
            )
        ,
            "115" => array(
                array("n_america", "california"),
                array("n_america", "colorado"),
                array("n_america", "connecticut")
            )
        ,
            "116" => array(
                array("n_america", "delaware"),
                array("n_america", "district of columbia"),
                array("n_america", "florida"),
                array("n_america", "georgia"),
                array("n_america", "hawaii")
            )
        ,
            "117" => array(
                array("n_america", "idaho"),
                array("n_america", "illinois"),
                array("n_america", "indiana"),
                array("n_america", "iowa")
            )
        ,
            "118" => array(
                array("n_america", "kansas"),
                array("n_america", "kentucky"),
                array("n_america", "louisianna")
            )
        ,
            "119" => array(
                array("n_america", "maine"),
                array("n_america", "maryland"),
                array("n_america", "massachusetts"),
                array("n_america", "michigan")
            )
        ,
            "120" => array(
                array("n_america", "minnesota"),
                array("n_america", "mississippi"),
                array("n_america", "missouri"),
                array("n_america", "montana")
            )
        ,
            "121" => array(
                array("n_america", "nebraska"),
                array("n_america", "nevada"),
                array("n_america", "new_hampshire"),
                array("n_america", "new_jersey")
            )
        ,
            "122" => array(
                array("n_america", "new_mexico"),
                array("n_america", "new_york"),
                array("n_america", "north_carolina"),
                array("n_america", "north_dakota")
            )
        ,
            "123" => array(
                array("n_america", "ohio"),
                array("n_america", "oklahoma"),
                array("n_america", "oregon"),
                array("n_america", "pennsylvania"),
                array("n_america", "rhode_island")
            )
        ,
            "124" => array(
                array("n_america", "south_carolina"),
                array("n_america", "south_dakota"),
                array("n_america", "tennessee"),
                array("n_america", "texas"),
                array("n_america", "texas_rio_grande_valley")
            )
        ,
            "125" => array(
                array("n_america", "utah"),
                array("n_america", "vermont"),
                array("n_america", "virginia")
            )
        ,
            "126" => array(
                array("n_america", "washington"),
                array("n_america", "west_virginia"),
                array("n_america", "wisconsin"),
                array("n_america", "wyoming")
            )
        ,
            "127" => array(
                array("c_america", "anguilla"),
                array("c_america", "antigua_and_barbuda"),
                array("c_america", "aruba"),
                array("c_america", "bahamas"),
                array("c_america", "barbados"),
                array("c_america", "belize"),
                array("c_america", "british_virgin_isles")
            )
        ,
            "128" => array(
                array("c_america", "cayman_islands"),
                array("c_america", "costa_rica"),
                array("c_america", "cuba"),
                array("c_america", "curacao"),
                array("c_america", "dominica"),
                array("c_america", "dominican_republic")
            )
        ,
            "129" => array(
                array("c_america", "el_salvador"),
                array("c_america", "grenada"),
                array("c_america", "guadaloupe"),
                array("c_america", "guatemala")
            )
        ,
            "130" => array(array("c_america", "haiti"), array("c_america", "honduras"), array("c_america", "jamaica"))
        ,
            "131" => array(
                array("c_america", "martinique"),
                array("c_america", "montserrat"),
                array("c_america", "netherlands_antilles"),
                array("c_america", "nicaragua")
            )
        ,
            "132" => array(
                array("c_america", "panama"),
                array("c_america", "puerto_rico"),
                array("c_america", "st_kitts_and_nevis"),
                array("c_america", "st_lucia"),
                array("c_america", "st_vincent_grenadines")
            )
        ,
            "133" => array(
                array("c_america", "trinidad_and_tobago"),
                array("c_america", "tobago"),
                array("c_america", "turks_and_caicos"),
                array("c_america", "us_virgin_isles")
            )
        ,
            "134" => array(
                array("s_america", "argentina_buenos_aires"),
                array("s_america", "argentina_buenos_aires_city")
            )
        ,
            "135" => array(
                array("s_america", "argentina_catamarca"),
                array("s_america", "argentina_chaco"),
                array("s_america", "argentina_chubut"),
                array("s_america", "argentina_cordoba"),
                array("s_america", "argentina_corrientes")
            )
        ,
            "136" => array(
                array("s_america", "argentina_entre_rios"),
                array("s_america", "argentina_formosa"),
                array("s_america", "argentina_jujuy"),
                array("s_america", "argentina_La_pampa"),
                array("s_america", "argentina_La_rioja")
            )
        ,
            "137" => array(
                array("s_america", "argentina_mendoza"),
                array("s_america", "argentina_misiones"),
                array("s_america", "argentina_neuquen"),
                array("s_america", "argentina_patagonia"),
                array("s_america", "argentina_rio_negro")
            )
        ,
            "138" => array(
                array("s_america", "argentina_salta"),
                array("s_america", "argentina_san_juan"),
                array("s_america", "argentina_san_luis"),
                array("s_america", "argentina_santa_cruz"),
                array("s_america", "argentina_santa_fe"),
                array("s_america", "argentina_santiago_del_estero")
            )
        ,
            "139" => array(array("s_america", "argentina_tierra_del_fuego"), array("s_america", "argentina_tucumain"))
        ,
            "140" => array(
                array("s_america", "acre"),
                array("s_america", "alagoas"),
                array("s_america", "amapa"),
                array("s_america", "amazonas"),
                array("s_america", "bahia")
            )
        ,
            "141" => array(
                array("s_america", "ceara"),
                array("s_america", "espirito_santo"),
                array("s_america", "federal_district_inc_brasilia"),
                array("s_america", "goias")
            )
        ,
            "142" => array(
                array("s_america", "maranhao"),
                array("s_america", "mato_grosso"),
                array("s_america", "mato_grosso_do_sul"),
                array("s_america", "minas_gerais ")
            )
        ,
            "143" => array(
                array("s_america", "para"),
                array("s_america", "paraiba"),
                array("s_america", "parana"),
                array("s_america", "pernambuco"),
                array("s_america", "piaui")
            )
        ,
            "144" => array(
                array("s_america", "rio_de_janeiro"),
                array("s_america", "rio_grande_do_norte"),
                array("s_america", "rio_grande_do_sul"),
                array("s_america", "rondonia"),
                array("s_america", "roraima")
            )
        ,
            "145" => array(
                array("s_america", "santa_catarina"),
                array("s_america", "sao_paulo"),
                array("s_america", "sergipe"),
                array("s_america", "tocantins")
            )
        ,
            "146" => array(
                array("s_america", "bolivia"),
                array("s_america", "chile"),
                array("s_america", "colombia"),
                array("s_america", "chile_easter_island")
            )
        ,
            "147" => array(
                array("s_america", "ecuador"),
                array("s_america", "ecuador_birding_northern_ecuador"),
                array("s_america", "ecuador_galapagos"),
                array("s_america", "falkland_islands"),
                array("s_america", "french_guiana"),
                array("s_america", "guyana")
            )
        ,
            "148" => array(
                array("s_america", "paraguay"),
                array("s_america", "peru"),
                array("s_america", "suriname"),
                array("s_america", "uruguay"),
                array("s_america", "venezuela")
            )
        ,
            "149" => array(array("world", "world"), array("antarctica", "antarctica"))
        ,
            "150" => array(
                array("commerce", "bird_food"),
                array("commerce", "books"),
                array("commerce", "books_bookshops"),
                array("commerce", "books_magazines"),
                array("commerce", "books_publishers")
            )
        ,
            "151" => array(
                array("commerce", "holiday_companies"),
                array("commerce", "optics"),
                array("commerce", "optics_manufacturers"),
                array("commerce", "optics_tripods")
            )
        ,
            "152" => array(
                array("commerce", "other"),
                array("commerce", "other_supplies"),
                array("commerce", "outdoor_clothing"),
                array("commerce", "tapes")
            )
        ,
            "153" => array(
                array("fun", "bird_humour"),
                array("fun", "facts"),
                array("fun", "hints_and_tips"),
                array("fun", "homepages"),
                array("fun", "other_animals_and_plants")
            )
        ,
            "154" => array(
                array("fun", "quizzes"),
                array("fun", "stamps"),
                array("fun", "top_tens"),
                array("fun", "urban_myths"),
                array("fun", "writers")
            )
        ,
            "155" => array(
                array("images_and_sound", "art_and_artists"),
                array("images_and_sound", "podcasts"),
                array("images_and_sound", "webcams")
            )
        ,
            "156" => array(
                array("images_and_sound", "bird_song"),
                array("images_and_sound", "digiscoping"),
                array("images_and_sound", "photos")
            )
        ,
            "157" => array(
                array("ornithology", "banding_and_ringing"),
                array("ornithology", "bird_fairs"),
                array("ornithology", "birders_and_ornithologists"),
                array("ornithology", "birding_organisations"),
                array("ornithology", "conservation"),
                array("ornithology", "courses"),
                array("ornithology", "identification"),
                array("ornithology", "journals"),
                array("ornithology", "migration")
            )
        ,
            "158" => array(
                array("ornithology", "museums"),
                array("ornithology", "names_and_taxonomy"),
                array("ornithology", "ornithology_homepage"),
                array("ornithology", "pelagics"),
                array("ornithology", "study_and_behaviour"),
                array("ornithology", "threatened_and_extinct_species"),
                array("ornithology", "weather_and_tides")
            )
        ,
            "159" => array(
                array("ornithology", "prunellidae"),
                array("ornithology", "formicariidae"),
                array("ornithology", "thamnophilidae"),
                array("ornithology", "grallariidae")
            )
        ,
            "160" => array(
                array("ornithology", "corcoracidae"),
                array("ornithology", "eurylaimidae"),
                array("ornithology", "oreoicidae"),
                array("ornithology", "climacteridae")
            )
        ,
            "161" => array(
                array("ornithology", "acanthizidae"),
                array("ornithology", "petroicidae"),
                array("ornithology", "timaliidae"),
                array("ornithology", "coerebidae"),
                array("ornithology", "platysteiridae")
            )
        ,
            "162" => array(
                array("ornithology", "panuridae"),
                array("ornithology", "cracticidae"),
                array("ornithology", "melanocharitidae"),
                array("ornithology", "paradisaeidae"),
                array("ornithology", "icteridae")
            )
        ,
            "163" => array(
                array("ornithology", "donacobiidae"),
                array("ornithology", "machaerirhynchidae"),
                array("ornithology", "ptilonorhynchidae"),
                array("ornithology", "dasyornithidae"),
                array("ornithology", "pityriaseidae")
            )
        ,
            "164" => array(
                array("ornithology", "eurylaimidae"),
                array("ornithology", "pycnonotidae"),
                array("ornithology", "emberizidae"),
                array("ornithology", "megaluridae"),
                array("ornithology", "malaconotidae")
            )
        ,
            "165" => array(
                array("ornithology", "aegithalidae"),
                array("ornithology", "cardinalidae"),
                array("ornithology", "cettidae")
            )
        ,
            "166" => array(
                array("ornithology", "muscicapidae"),
                array("ornithology", "paridae"),
                array("ornithology", "cisticolidae"),
                array("ornithology", "cotingidae"),
            )
        ,
            "167" => array(
                array("ornithology", "certhiidae"),
                array("ornithology", "melanopareiidae"),
                array("ornithology", "paramythiidae"),
                array("ornithology", "macrosphenidae"),
                array("ornithology", "corvidae"),
                array("ornithology", "campephagidae")
            )
        ,
            "168" => array(
                array("ornithology", "arcanatoridae"),
                array("ornithology", "cinclidae"),
                array("ornithology", "dicruridae"),
                array("ornithology", "elachuridae"),
                array("ornithology", "stenostiridae"),
                array("ornithology", "stenostiridae"),
                array("ornithology", "irenidae")
            )
        ,
            "169" => array(
                array("ornithology", "maluridae"),
                array("ornithology", "rhipiduridae"),
                array("ornithology", "fringillidae"),
                array("ornithology", "dicaeidae"),
                array("ornithology", "muscicapidae")
            )
        ,
            "170" => array(
                array("ornithology", "pellorneidae"),
                array("ornithology", "acanthizidae"),
                array("ornithology", "polioptilidae"),
                array("ornithology", "conopophagidae"),
                array("ornithology", "megaluridae"),
                array("ornithology", "locustellidae")
            )
        ,
            "171" => array(
                array("ornithology", "vireonidae"),
                array("ornithology", "cardinalidae"),
                array("ornithology", "prionopidae"),
                array("ornithology", "meliphagidae"),
                array("ornithology", "hyliotidae")
            )
        ,
            "172" => array(
                array("ornithology", "hypocoliidae"),
                array("ornithology", "ifritidae"),
                array("ornithology", "viduidae"),
                array("ornithology", "aegithinidae"),
                array("ornithology", "corvidae"),
                array("ornithology", "psophodidae")
            )
        ,
            "173" => array(
                array("ornithology", "regulidae"),
                array("ornithology", "alaudidae"),
                array("ornithology", "leiothrichidae"),
                array("ornithology", "phylloscopidae"),
                array("ornithology", "chloropseidae")
            )
        ,
            "174" => array(
                array("ornithology", "orthonychidae"),
                array("ornithology", "aegithalidae"),
                array("ornithology", "melanocharitidae"),
                array("ornithology", "calcariidae"),
                array("ornithology", "menuridae")
            )
        ,
            "175" => array(
                array("ornithology", "monarchidae"),
                array("ornithology", "bernieridae"),
                array("ornithology", "pipridae"),
                array("ornithology", "acrocephalidae"),
                array("ornithology", "hirundinidae")
            )
        ,
            "176" => array(
                array("ornithology", "megaluridae"),
                array("ornithology", "mimidae"),
                array("ornithology", "monarchidae"),
                array("ornithology", "rhagologidae"),
                array("ornithology", "estrildidae")
            )
        ,
            "177" => array(
                array("ornithology", "sturnidae"),
                array("ornithology", "callaeidae"),
                array("ornithology", "acanthisittidae"),
                array("ornithology", "nicatoridae"),
                array("ornithology", "sittidae")
            )
        ,
            "178" => array(
                array("ornithology", "mohoidae"),
                array("ornithology", "sylviidae"),
                array("ornithology", "peucedramidae"),
                array("ornithology", "oriolidae")
            )
        ,
            "179" => array(
                array("ornithology", "furnariidae"),
                array("ornithology", "buphagidae"),
                array("ornithology", "dulidae"),
                array("ornithology", "pardalotidae")
            )
        ,
            "180" => array(
                array("ornithology", "timaliidae"),
                array("ornithology", "remizidae"),
                array("ornithology", "motacillidae"),
                array("ornithology", "pittidae"),
                array("ornithology", "cotingidae")
            )
        ,
            "181" => array(
                array("ornithology", "eulacestomatidae"),
                array("ornithology", "urocynchramidae"),
                array("ornithology", "pomatostomidae"),
                array("ornithology", "psophodidae"),
                array("ornithology", "eupetidae"),
                array("ornithology", "rhabdornithidae")
            )
        ,
            "182" => array(
                array("ornithology", "picathartidae"),
                array("ornithology", "chaetopidae"),
                array("ornithology", "cnemophilidae"),
                array("ornithology", "atrichornithidae"),
                array("ornithology", "oxyruncidae")
            )
        ,
            "183" => array(
                array("ornithology", "colluricinclidae"),
                array("ornithology", "ptilogonatidae"),
                array("ornithology", "neosittidae"),
                array("ornithology", "calcariidae"),
                array("ornithology", "emberizidae")
            )
        ,
            "184" => array(
                array("ornithology", "passeridae"),
                array("ornithology", "nectariniidae"),
                array("ornithology", "sturnidae"),
                array("ornithology", "notiomystidae"),
                array("ornithology", "scotocercidae"),
                array("ornithology", "promeropidae")
            )
        ,
            "185" => array(
                array("ornithology", "nectariniidae"),
                array("ornithology", "hirundinidae"),
                array("ornithology", "thraupidae"),
                array("ornithology", "rhinocryptidae"),
                array("ornithology", "mimidae")
            )
        ,
            "186" => array(
                array("ornithology", "turdidae"),
                array("ornithology", "paramythiidae"),
                array("ornithology", "paridae"),
                array("ornithology", "tityridae"),
                array("ornithology", "laniidae")
            )
        ,
            "187" => array(
                array("ornithology", "tyrannidae"),
                array("ornithology", "vangidae"),
                array("ornithology", "vireonidae"),
                array("ornithology", "motacillidae"),
                array("ornithology", "tichodromidae")
            )
        ,
            "188" => array(
                array("ornithology", "parulidae"),
                array("ornithology", "platysteiridae"),
                array("ornithology", "estrildidae"),
                array("ornithology", "bombycillidae"),
                array("ornithology", "ploceidae")
            )
        ,
            "189" => array(
                array("ornithology", "psophodidae"),
                array("ornithology", "pachycephalidae"),
                array("ornithology", "zosteropidae"),
                array("ornithology", "mohouidae"),
                array("ornithology", "viduidae"),
                array("ornithology", "ploceidae")
            )
        ,
            "190" => array(
                array("ornithology", "furnariidae"),
                array("ornithology", "tephrodornithidae"),
                array("ornithology", "artamidae"),
                array("ornithology", "pnoepygidae"),
                array("ornithology", "troglodytidae"),
                array("ornithology", "erythroceridae"),
                array("ornithology", "hylocitreidae"),
                array("ornithology", "incertae_sedis")
            )
        ,
            "191" => array(
                array("ornithology", "tinamidae"),
                array("ornithology", "struthionidae"),
                array("ornithology", "rheidae"),
                array("ornithology", "casuariidae"),
                array("ornithology", "dromaiidae")
            )
        ,
            "192" => array(
                array("ornithology", "apterygidae"),
                array("ornithology", "anhimidae"),
                array("ornithology", "anseranatidae"),
                array("ornithology", "anatidae"),
                array("ornithology", "megapodiidae")
            )
        ,
            "193" => array(
                array("ornithology", "cracidae"),
                array("ornithology", "numididae"),
                array("ornithology", "odontophoridae"),
                array("ornithology", "phasianidae"),
                array("ornithology", "gaviidae")
            )
        ,
            "194" => array(
                array("ornithology", "spheniscidae"),
                array("ornithology", "diomedeidae"),
                array("ornithology", "procellariidae"),
                array("ornithology", "hydrobatidae"),
                array("ornithology", "pelecanoididae")
            )
        ,
            "195" => array(
                array("ornithology", "podicipedidae"),
                array("ornithology", "phoenicopteridae"),
                array("ornithology", "phaethontidae"),
                array("ornithology", "ciconiidae"),
                array("ornithology", "threskiornithidae")
            )
        ,
            "196" => array(
                array("ornithology", "ardeidae"),
                array("ornithology", "scopidae"),
                array("ornithology", "balaenicipitidae"),
                array("ornithology", "pelecanidae"),
                array("ornithology", "fregatidae")
            )
        ,
            "197" => array(
                array("ornithology", "sulidae"),
                array("ornithology", "phalacrocoracidae"),
                array("ornithology", "anhingidae"),
                array("ornithology", "cathartidae"),
                array("ornithology", "sagittariidae")
            )
        ,
            "198" => array(
                array("ornithology", "pandionidae"),
                array("ornithology", "accipitridae"),
                array("ornithology", "otididae"),
                array("ornithology", "mesitornithidae"),
                array("ornithology", "cariamidae")
            )
        ,
            "199" => array(
                array("ornithology", "rhynochetidae"),
                array("ornithology", "eurypygidae"),
                array("ornithology", "sarothruridae"),
                array("ornithology", "heliornithidae"),
                array("ornithology", "rallidae")
            )
        ,
            "200" => array(
                array("ornithology", "psophiidae"),
                array("ornithology", "gruidae"),
                array("ornithology", "aramidae"),
                array("ornithology", "turnicidae"),
                array("ornithology", "burhinidae")
            )
        ,
            "201" => array(
                array("ornithology", "chionidae"),
                array("ornithology", "pluvianellidae"),
                array("ornithology", "haematopodidae"),
                array("ornithology", "dromadidae"),
                array("ornithology", "ibidorhynchidae")
            )
        ,
            "202" => array(
                array("ornithology", "recurvirostridae"),
                array("ornithology", "charadriidae"),
                array("ornithology", "pluvianidae"),
                array("ornithology", "rostratulidae"),
                array("ornithology", "jacanidae")
            )
        ,
            "203" => array(
                array("ornithology", "pedionomidae"),
                array("ornithology", "thinocoridae"),
                array("ornithology", "scolopacidae"),
                array("ornithology", "glareolidae"),
                array("ornithology", "laridae")
            )
        ,
            "204" => array(
                array("ornithology", "stercorariidae"),
                array("ornithology", "alcidae"),
                array("ornithology", "pteroclidae"),
                array("ornithology", "columbidae"),
                array("ornithology", "opisthocomidae")
            )
        ,
            "205" => array(
                array("ornithology", "musophagidae"),
                array("ornithology", "cuculidae"),
                array("ornithology", "tytonidae"),
                array("ornithology", "strigidae"),
                array("ornithology", "podargidae")
            )
        ,
            "206" => array(
                array("ornithology", "steatornithidae"),
                array("ornithology", "nyctibiidae"),
                array("ornithology", "caprimulgidae"),
                array("ornithology", "aegothelidae"),
                array("ornithology", "hemiprocnidae")
            )
        ,
            "207" => array(
                array("ornithology", "apodidae"),
                array("ornithology", "trochilidae"),
                array("ornithology", "coliidae"),
                array("ornithology", "trogonidae"),
                array("ornithology", "leptosomidae")
            )
        ,
            "208" => array(
                array("ornithology", "coraciidae"),
                array("ornithology", "brachypteraciidae"),
                array("ornithology", "alcedinidae"),
                array("ornithology", "todidae"),
                array("ornithology", "momotidae")
            )
        ,
            "209" => array(
                array("ornithology", "meropidae"),
                array("ornithology", "upupidae"),
                array("ornithology", "phoeniculidae"),
                array("ornithology", "bucerotidae"),
                array("ornithology", "bucorvidae")
            )
        ,
            "210" => array(
                array("ornithology", "galbulidae"),
                array("ornithology", "bucconidae"),
                array("ornithology", "capitonidae"),
                array("ornithology", "semnornithidae"),
                array("ornithology", "ramphastidae")
            )
        ,
            "211" => array(
                array("ornithology", "megalaimidae"),
                array("ornithology", "lybiidae"),
                array("ornithology", "indicatoridae"),
                array("ornithology", "picidae"),
                array("ornithology", "falconidae")
            )
        ,
            "212" => array(
                array("signpost", "birdlines"),
                array("signpost", "birds_and_angling"),
                array("signpost", "birds_and_gardening"),
                array("signpost", "blogs")
            )
        ,
            "213" => array(
                array("signpost", "disabled_birding"),
                array("signpost", "mailing_lists"),
                array("signpost", "mega_links_pages"),
                array("signpost", "webrings")
            )
        ,
            "214" => array(
                array("listing_and_racing", "big_days_and_bird_racing"),
                array("listing_and_racing", "big_sits")
            )
        ,
            "215" => array(
                array("listing_and_racing", "listing"),
                array("listing_and_racing", "twitching"),
                array("listing_and_racing", "web_twitching")
            )
        );
    }

    //    ,"130" => array(array("ornithology", ""))
    //      "), array("ornithology", "

    public function getPageStart()
    {
        $code = '<!DOCTYPE html>
<html>';
        $code .= '
	<head>
		<title>' . ucwords($this->sectionTitle) . '</title>
		<link rel="stylesheet" href="../css/normalize.css">
		<link rel="stylesheet" href="css/linkChecker.css">
	</head>';
        $code .= '<body>
		<h1>' . ucwords($this->sectionTitle) . '</h1>';
        return $code;
    }

    public function getPageEnd()
    {
        return '
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="../js/admin.js"></script>
		</body></html>';
    }

    public function getDisplayAdmin()
    {
        //form to choose what to check
        $output = '<form id="linkform" action="' . $_SERVER['PHP_SELF'] . '" method="POST">';
        $output .= '<input type="hidden" name="mode" value="check" />';
        $output .= '<select name="linktarget">';
        $output .= '<option value = "0">Please pick below</option>';
        $output .= '<optgroup label = "Africa">';
        $output .= '<option value="1">Africa & Africa A</option>';
        $output .= '<option value="2">Africa B</option>';
        $output .= '<option value="3">Africa C</option>';
        $output .= '<option value="4">Africa D</option>';
        $output .= '<option value="5">Africa E</option>';
        $output .= '<option value="6">Africa G</option>';
        $output .= '<option value="7">Africa K & L</option>';
        $output .= '<option value="8">Africa M</option>';
        $output .= '<option value="9">Africa N</option>';
        $output .= '<option value="10">Africa R</option>';
        $output .= '<option value="11">Africa S</option>';
        $output .= '<option value="12">Africa - South Africa A - K</option>';
        $output .= '<option value="13">Africa - South Africa L - Z</option>';
        $output .= '<option value="14">Africa T</option>';
        $output .= '<option value="15">Africa U - Z</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "Asia">';
        $output .= '<option value="16">Asia & Asia A - C</option>';
        $output .= '<option value="17">Asia - China & China A - F</option>';
        $output .= '<option value="18">Asia - China G</option>';
        $output .= '<option value="19">Asia - China H</option>';
        $output .= '<option value="20">Asia - China J - N</option>';
        $output .= '<option value="21">Asia - China Q - S</option>';
        $output .= '<option value="22">Asia - China T - Z</option>';
        $output .= '<option value="23">Asia - India & India A - B</option>';
        $output .= '<option value="24">Asia - India C - H</option>';
        $output .= '<option value="25">Asia - India J - L</option>';
        $output .= '<option value="26">Asia - India M</option>';
        $output .= '<option value="27">Asia - India N - S</option>';
        $output .= '<option value="28">Asia - India T - W</option>';
        $output .= '<option value="29">Asia - Indonesia & Indonesia B - J</option>';
        $output .= '<option value="30">Asia - Indonesia K</option>';
        $output .= '<option value="31">Asia - Indonesia M - S</option>';
        $output .= '<option value="32">Asia J - L</option>';
        $output .= '<option value="33">Asia - Malaysia & Malaysia A - F</option>';
        $output .= '<option value="34">Asia - Malaysia M - P</option>';
        $output .= '<option value="35">Asia - Malaysia S - T</option>';
        $output .= '<option value="36">Asia M - N</option>';
        $output .= '<option value="37">Asia P - S</option>';
        $output .= '<option value="38">Asia T - V</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "Australasia">';
        $output .= '<option value="39">Australasia & Australia A - N</option>';
        $output .= '<option value="40">Australasia & Australia Q - W</option>';
        $output .= '<option value="41">Australasia A - F</option>';
        $output .= '<option value="42">Australasia G - M</option>';
        $output .= '<option value="43">Australasia N</option>';
        $output .= '<option value="44">Australasia P - S</option>';
        $output .= '<option value="45">Australasia T - W</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "Europe">';
        $output .= '<option value="46">Europe & Europe A</option>';
        $output .= '<option value="47">Europe B</option>';
        $output .= '<option value="48">Europe C - F</option>';
        $output .= '<option value="49">Europe - France & France A - B</option>';
        $output .= '<option value="50">Europe - France C - F</option>';
        $output .= '<option value="51">Europe - France L - N</option>';
        $output .= '<option value="52">Europe - France P = R</option>';
        $output .= '<option value="53">Europe - Germany & Germany B</option>';
        $output .= '<option value="54">Europe - Germany H - R</option>';
        $output .= '<option value="55">Europe - Germany S - T</option>';
        $output .= '<option value="56">Europe G - I</option>';
        $output .= '<option value="57">Europe - Greece & islands</option>';
        $output .= '<option value="58">Europe - Ireland & Ireland C</option>';
        $output .= '<option value="59">Europe - Ireland D - K</option>';
        $output .= '<option value="60">Europe - Ireland L</option>';
        $output .= '<option value="61">Europe - Ireland M - R</option>';
        $output .= '<option value="62">Europe - Ireland S - W</option>';
        $output .= '<option value="63">Europe - Italy & islands</option>';
        $output .= '<option value="64">Europe K - L</option>';
        $output .= '<option value="65">Europe M</option>';
        $output .= '<option value="66">Europe N - R</option>';
        $output .= '<option value="67">Europe Portugal & regions</option>';
        $output .= '<option value="68">Europe Russia & Russia A - C</option>';
        $output .= '<option value="69">Europe Russia E - N</option>';
        $output .= '<option value="70">Europe Russia R - W</option>';
        $output .= '<option value="71">Europe S</option>';
        $output .= '<option value="72">Europe Spain & Spain A - B</option>';
        $output .= '<option value="73">Europe Spain C</option>';
        $output .= '<option value="74">Europe Spain E - M</option>';
        $output .= '<option value="75">Europe Spain N - V</option>';
        $output .= '<option value="76">Europe T - U</option>';
        $output .= '<option value="77">Europe UK Countries & Islands</option>';
        $output .= '<option value="78">Europe England A - B</option>';
        $output .= '<option value="79">Europe England C</option>';
        $output .= '<option value="80">Europe England D - E</option>';
        $output .= '<option value="81">Europe England G - H</option>';
        $output .= '<option value="82">Europe England I - L</option>';
        $output .= '<option value="83">Europe England M - O</option>';
        $output .= '<option value="84">Europe England S</option>';
        $output .= '<option value="85">Europe England T - W</option>';
        $output .= '<option value="86">Europe England Y</option>';
        $output .= '<option value="87">Europe Northern Ireland</option>';
        $output .= '<option value="88">Europe Scotland A</option>';
        $output .= '<option value="89">Europe Scotland C</option>';
        $output .= '<option value="90">Europe Scotland D - E</option>';
        $output .= '<option value="91">Europe Scotland F - M</option>';
        $output .= '<option value="92">Europe Scotland N - R</option>';
        $output .= '<option value="93">Europe Scotland S</option>';
        $output .= '<option value="94">Europe Scotland W</option>';
        $output .= '<option value="95">Europe Wales A - B</option>';
        $output .= '<option value="96">Europe Wales C</option>';
        $output .= '<option value="97">Europe Wales D - M</option>';
        $output .= '<option value="98">Europe Wales N - P</option>';
        $output .= '<option value="99">Europe Wales R - W</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "Middle East">';
        $output .= '<option value="100">Middle East A - I</option>';
        $output .= '<option value="101">Middle East J - P</option>';
        $output .= '<option value="102">Middle East Q - Y</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "North America">';
        $output .= '<option value="103">North America Countries & Islands</option>';
        $output .= '<option value="104">North America Canada A - M</option>';
        $output .= '<option value="105">North America Canada N</option>';
        $output .= '<option value="106">North America Canada O - Y</option>';
        $output .= '<option value="107">North America Mexico A - B</option>';
        $output .= '<option value="108">North America Mexico C</option>';
        $output .= '<option value="109">North America Mexico D - J</option>';
        $output .= '<option value="110">North America Mexico M - N</option>';
        $output .= '<option value="111">North America Mexico O - Q</option>';
        $output .= '<option value="112">North America Mexico S - T</option>';
        $output .= '<option value="113">North America Mexico V - Z</option>';
        $output .= '<option value="114">North America USA A</option>';
        $output .= '<option value="115">North America USA C</option>';
        $output .= '<option value="116">North America USA D - H</option>';
        $output .= '<option value="117">North America USA I</option>';
        $output .= '<option value="118">North America USA K - L</option>';
        $output .= '<option value="119">North America USA M1</option>';
        $output .= '<option value="120">North America USA M2</option>';
        $output .= '<option value="121">North America USA N1</option>';
        $output .= '<option value="122">North America USA N2</option>';
        $output .= '<option value="123">North America USA O - R</option>';
        $output .= '<option value="124">North America USA S - T</option>';
        $output .= '<option value="125">North America USA U - V</option>';
        $output .= '<option value="126">North America USA W</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "Central America">';
        $output .= '<option value="127">Central America A - B</option>';
        $output .= '<option value="128">Central America C - D</option>';
        $output .= '<option value="129">Central America E - G</option>';
        $output .= '<option value="130">Central America H - J</option>';
        $output .= '<option value="131">Central America M - N</option>';
        $output .= '<option value="132">Central America P - S</option>';
        $output .= '<option value="133">Central America T - U</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "South America">';
        $output .= '<option value="134">South America Argentina B</option>';
        $output .= '<option value="135">South America Argentina C</option>';
        $output .= '<option value="136">South America Argentina E - L</option>';
        $output .= '<option value="137">South America Argentina M - R</option>';
        $output .= '<option value="138">South America Argentina S</option>';
        $output .= '<option value="139">South America Argentina T</option>';
        $output .= '<option value="140">South America Brazil A = B</option>';
        $output .= '<option value="141">South America Brazil C - G</option>';
        $output .= '<option value="142">South America Brazil M</option>';
        $output .= '<option value="143">South America Brazil P</option>';
        $output .= '<option value="144">South America Brazil R</option>';
        $output .= '<option value="145">South America Brazil S - T</option>';
        $output .= '<option value="146">South America B - CS</option>';
        $output .= '<option value="147">South America E - G</option>';
        $output .= '<option value="148">South America P - V</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "World">';
        $output .= '<option value="149">World & Antarctica</option>';
        $output .= '</optgroup>';
        $output .= '<optgroup label = "Non Geo pages">';
        $output .= '<option value="150">Commerce 1</option>';
        $output .= '<option value="151">Commerce 2</option>';
        $output .= '<option value="152">Commerce 3</option>';
        $output .= '<option value="153">Fun 1</option>';
        $output .= '<option value="154">Fun 2</option>';
        $output .= '<option value="155">Images and Sound 1</option>';
        $output .= '<option value="156">Images and Sound 2</option>';
        $output .= '<option value="157">Ornithology 1</option>';
        $output .= '<option value="158">Ornithology 2</option>';
        $output .= '<option value="159">Passerines 1</option>';
        $output .= '<option value="160">Passerines 2</option>';
        $output .= '<option value="161">Passerines 3</option>';
        $output .= '<option value="162">Passerines 4</option>';
        $output .= '<option value="163">Passerines 5</option>';
        $output .= '<option value="164">Passerines 6</option>';
        $output .= '<option value="165">Passerines 7</option>';
        $output .= '<option value="166">Passerines 8</option>';
        $output .= '<option value="167">Passerines 9</option>';
        $output .= '<option value="168">Passerines 10</option>';
        $output .= '<option value="169">Passerines 11</option>';
        $output .= '<option value="170">Passerines 12</option>';
        $output .= '<option value="171">Passerines 13</option>';
        $output .= '<option value="172">Passerines 14</option>';
        $output .= '<option value="173">Passerines 15</option>';
        $output .= '<option value="174">Passerines 16</option>';
        $output .= '<option value="175">Passerines 17</option>';
        $output .= '<option value="176">Passerines 18</option>';
        $output .= '<option value="177">Passerines 19</option>';
        $output .= '<option value="178">Passerines 20</option>';
        $output .= '<option value="179">Passerines 21</option>';
        $output .= '<option value="180">Passerines 22</option>';
        $output .= '<option value="181">Passerines 23</option>';
        $output .= '<option value="182">Passerines 24</option>';
        $output .= '<option value="183">Passerines 25</option>';
        $output .= '<option value="184">Passerines 26</option>';
        $output .= '<option value="185">Passerines 27</option>';
        $output .= '<option value="186">Passerines 28</option>';
        $output .= '<option value="187">Passerines 29</option>';
        $output .= '<option value="188">Passerines 30</option>';
        $output .= '<option value="189">Passerines 31</option>';
        $output .= '<option value="190">Passerines 32</option>';
        $output .= '<option value="191">Non-passerines 1</option>';
        $output .= '<option value="192">Non-passerines 2</option>';
        $output .= '<option value="193">Non-passerines 3</option>';
        $output .= '<option value="194">Non-passerines 4</option>';
        $output .= '<option value="195">Non-passerines 5</option>';
        $output .= '<option value="196">Non-passerines 6</option>';
        $output .= '<option value="197">Non-passerines 7</option>';
        $output .= '<option value="198">Non-passerines 8</option>';
        $output .= '<option value="199">Non-passerines 9</option>';
        $output .= '<option value="200">Non-passerines 10</option>';
        $output .= '<option value="201">Non-passerines 11</option>';
        $output .= '<option value="202">Non-passerines 12</option>';
        $output .= '<option value="203">Non-passerines 13</option>';
        $output .= '<option value="204">Non-passerines 14</option>';
        $output .= '<option value="205">Non-passerines 15</option>';
        $output .= '<option value="206">Non-passerines 16</option>';
        $output .= '<option value="207">Non-passerines 17</option>';
        $output .= '<option value="208">Non-passerines 18</option>';
        $output .= '<option value="209">Non-passerines 19</option>';
        $output .= '<option value="210">Non-passerines 20</option>';
        $output .= '<option value="211">Non-passerines 21</option>';
        $output .= '<option value="212">Signpost 1</option>';
        $output .= '<option value="213">Signpost 2</option>';
        $output .= '<option value="214">Listing and Racing 1</option>';
        $output .= '<option value="215">Listing and Racing 2</option>';
        $output .= '</optgroup>';

        $output .= '</select>';
        $output .= '<input type="submit" value="Check" />';
        $output .= '<form>';
        return $output;
    }

    public function getResults($target)
    {
        $targetList = $this->getData($target);
        $output = "";
        foreach ($targetList as $targetPage) {
            $pageCode = $this->getPageCode($targetPage[0], $targetPage[1]);
            $linkList = $this->getLinkList($pageCode);
            // $linkList = array(array("text"=>"test", "url"=>"http://fatbirder.com", "result"=>""));
            $checkedLinkList = $this->checkLinks($linkList);
            $output .= $this->displayLinks($checkedLinkList, $targetPage[1]);
        }
        return $output;
    }

    private function getPageCode($category, $subcategory, $article = "", $tmpl_name = "templateLinkChecker.html")
    {
        $page = new Page();
        $code = $page->createPage($tmpl_name, $category, $subcategory, $article);
        return $code;
    }

    private function getLinkList($code)
    {
        $doc = new DOMDocument();
        $doc->loadHTML($code);
        $article = $doc->getElementsByTagName("a");
        $linkList = array();
        foreach ($article as $node) {
            $href = $node->getAttribute("href");
            if (strpos($href, "mailto") === false) {
                $linkList[] = array("url" => $href, "text" => $node->nodeValue, "result" => "");
            }
        }
        return $linkList;
    }

    private function checkLinks($linkList)
    {
        foreach ($linkList as &$url) {
            set_time_limit(30);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url['url']);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_NOBODY, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_exec($ch);
            $linkInfo = curl_getinfo($ch);
            $url['result'] = $linkInfo['http_code'];
            $url['time'] = $linkInfo['total_time'];
            curl_close($ch);
        }
        return $linkList;
    }

    private function displayLinks($linkList, $pageName)
    {
        $output = '<h2></h2>';
        $output .= '<table><caption>' . $pageName . '</caption><tr>
        <th>URL text</th>
        <th>URL</th>
        <th>Test Link</th>
        <th>Result</th>
        <th>Time</th></tr>';
        foreach ($linkList as $link) {
            if ($link['result'] < 200 || $link['result'] > 399) {
                $output .= '<tr class="fail">';
            } else {
                $output .= '<tr>';
            }
            $output .= '<td>' . $link['text'] . '</td>';
            $output .= '<td>' . $link['url'] . '</td>';
            $output .= '<td>' . '<a href="' . $link['url'] . '">Test link</a></td>';
            $output .= '<td>' . $link['result'] . '</td>';
            $output .= '<td>' . $link['time'] . '</td>';
        }
        $output .= '</table>';
        return $output;
    }

    private function getData($target)
    {
        $result = $this->pageForLinkChecker[$target];
        return $result;
    }

}