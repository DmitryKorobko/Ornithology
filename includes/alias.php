<?php

/*
 *
 *
 *
 */

class Urlalias
{

    private $newcat;
    private $newsubcat;

    public function __construct($cat, $subcat)
    {
        $this->newcat = $cat;
        $this->newsubcat = $subcat;
        $this->getData();
    }

    public function getNewCat()
    {
        return $this->newcat;
    }

    public function getNewSubCat()
    {
        return $this->newsubcat;
    }

    private function getData()
    {
        if ($this->newsubcat == "links_geo" || ($this->newcat == "links_geo" && $this->newsubcat == "")) {
            $this->newcat = "world";
            $this->newsubcat = "world";
        }
        if ($this->newcat == "links_geo" && $this->newsubcat != "world") {
            $this->newcat = $this->newsubcat;
        }
        if ($this->newcat == "europe") {
            if ($this->newsubcat == "europe") {
                $this->newsubcat = "europe_general";
            }
            if ($this->newsubcat == "uk") {
                $this->newsubcat = "united_kingdom_general";
            }
            if ($this->newsubcat == "england") {
                $this->newsubcat = "england_general";
            }
            if ($this->newsubcat == "scotland") {
                $this->newsubcat = "scotland_general";
            }
            if ($this->newsubcat == "wales") {
                $this->newsubcat = "wales_general";
            }
            if ($this->newsubcat == "england_channel_islands") {
                $this->newsubcat = "united_kingdom_channel_islands";
            }
            if ($this->newsubcat == "england_scilly_isles") {
                $this->newsubcat = "united_kingdom_scilly_isles";
            }
            if ($this->newsubcat == "england_isle_of_man") {
                $this->newsubcat = "united_kingdom_isle_of_man";
            }
            if ($this->newsubcat == "ireland") {
                $this->newsubcat = "irish_republic";
            }
            if ($this->newsubcat == "spain_castilla-la_mancha") {
                $this->newsubcat = "spain_castilla-la mancha";
            }
            if ($this->newsubcat == "spain_cataluna") {
                $this->newsubcat = "spain_catalonia";
            }
        }
        if ($this->newcat == "america_north" && $this->newsubcat == "america_north") {
            $this->newcat = "n_america";
            $this->newsubcat = "n_america";
        }
        if ($this->newcat == "america_north") {
            $this->newcat = "n_america";
        }
        if ($this->newcat == "america_south" && $this->newsubcat == "america_south") {
            $this->newcat = "s_america";
            $this->newsubcat = "s_america";
        }
        if ($this->newcat == "america_south") {
            $this->newcat = "s_america";
            if (stripos($this->newsubcat, "brazil_") === 0) {
                $this->newsubcat = str_ireplace("brazil_", "", $this->newsubcat);
            }
        }
        if ($this->newcat == "america_central" && $this->newsubcat == "america_central") {
            $this->newcat = "c_america";
            $this->newsubcat = "c_america";
        }
        if ($this->newcat == "america_central") {
            $this->newcat = "c_america";
        }
        if ($this->newcat == "c_america" && $this->newsubcat == "guadeloupe") {
            $this->newsubcat = "guadaloupe";
        }
        if ($this->newcat == "c_america" && $this->newsubcat == "montserrat") {
            $this->newsubcat = "monserrat";
        }
        if ($this->newcat == "america_canada" && $this->newsubcat == "index") {
            $this->newcat = "n_america";
            $this->newsubcat = "canada";
        }
        if ($this->newcat == "america_canada") {
            $this->newcat = "n_america";
        }
        if ($this->newcat == "america_united_states" && $this->newsubcat == "index") {
            $this->newcat = "n_america";
            $this->newsubcat = "united_states";
        }
        if ($this->newcat == "america_united_states") {
            $this->newcat = "n_america";
        }
        if ($this->newsubcat == "india_odisha") {
            $this->newsubcat = "india_orrisa";
        }
        if ($this->newsubcat == "australia_christmas_island") {
            $this->newsubcat = "christmas_island";
        }
        if (($this->newcat == "species_and_families" && $this->newsubcat == "passerines") || ($this->newcat == "passerines" && $this->newsubcat == "")) {
            $this->newcat = "ornithology";
            $this->newsubcat = "species_and_families_homepage_passerines";
        }
        if (($this->newcat == "species_and_families" && $this->newsubcat == "non_passerines") || ($this->newcat == "non_passerines" && $this->newsubcat == "")) {
            $this->newcat = "ornithology";
            $this->newsubcat = "species_and_families_homepage_non_passerines";
        }
        if ($this->newcat == "passerines" || $this->newcat == "non_passerines") {
            $this->newcat = "ornithology";
        }
        if ($this->newcat == "travel" && $this->newsubcat == "holiday_companies") {
            $this->newcat = "commerce";
        }
        if ($this->newcat == "travel" && $this->newsubcat == "pelagics") {
            $this->newcat = "ornithology";
        }
        if (($this->newcat == "links" && $this->newsubcat == "trip_reports") || ($this->newcat == "trip_reports" && $this->newsubcat == "")) {
            $this->newcat = "trip_reports";
            $this->newsubcat = "index";
        }
        if ($this->newcat == "signpost_and_discussion") {
            $this->newcat = "signpost";
        }
        if ($this->newcat == "fun" && $this->newsubcat == "misc") {
            $this->newsubcat = "homepages";
        }
        if (($this->newcat == "about" && $this->newsubcat == "index") || ($this->newcat == "about" && $this->newsubcat == "")) {
            $this->newcat = "default_about";
            $this->newsubcat = "default_about";
        }
    }
}