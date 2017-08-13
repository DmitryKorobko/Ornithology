<?php
include("../common/funct_lib.php");
connect_db();

//set table name variables
$table = "links";

//alternative name for drop list fucntion
function alt_name($db_name)
{
    switch ($db_name) {
        case"Accipitridae":
            $db_name = "Hawks & Eagles (Accipitridae)";
            break;
        case"Aegothelidae":
            $db_name = "Owlet-Nightjars (Aegothelidae)";
            break;
        case"Alcedinidae":
            $db_name = "Kingfishers (Alcedinidae)";
            break;
        case"Alcidae":
            $db_name = "Auks (Alcidae)";
            break;
        case"Anatidae":
            $db_name = "Swans, Geese & Ducks (Anatidae)";
            break;
        case"Anhimidae":
            $db_name = "Screamers (Anhimidae)";
            break;
        case"Anhingidae":
            $db_name = "Anhinga & Darters (Anhingidae)";
            break;
        case"Anseranatidae":
            $db_name = "Magpie Goose (Anseranatidae)";
            break;
        case"Apodidae":
            $db_name = "Typical Swifts (Apodidae)";
            break;
        case"Apterygidae":
            $db_name = "Kiwis (Apterygidae)";
            break;
        case"Aramidae":
            $db_name = "Limpkin (Aramidae)";
            break;
        case"Ardeidae":
            $db_name = "Herons (Ardeidae)";
            break;
        case"Balaenicipitidae":
            $db_name = "Shoebill (Balaenicipitidae)";
            break;
        case"Brachypteraciidae":
            $db_name = "Ground-Rollers (Brachypteraciidae)";
            break;
        case"Bucconidae":
            $db_name = "Puffbirds (Bucconidae)";
            break;
        case"Bucerotidae":
            $db_name = "Typical Hornbills (Bucerotidae)";
            break;
        case"Bucorvidae":
            $db_name = "Ground-Hornbills (Bucorvidae)";
            break;
        case"Burhinidae":
            $db_name = "Thick-knees (Burhinidae)";
            break;
        case"Cacatuidae":
            $db_name = "Cockatoos (Cacatuidae)";
            break;
        case"Capitonidae":
            $db_name = "New World Barbets (Capitonidae)";
            break;
        case"Caprimulgidae":
            $db_name = "Nightjars (Caprimulgidae)";
            break;
        case"Cariamidae":
            $db_name = "Seriemas (Cariamidae)";
            break;
        case"Casuariidae":
            $db_name = "Cassowaries (Casuariidae)";
            break;
        case"Cathartidae":
            $db_name = "New World Vultures (Cathartidae)";
            break;
        case"Centropodidae":
            $db_name = "Coucal (Centropodidae)";
            break;
        case"Charadriidae":
            $db_name = "Plovers (Charadriidae)";
            break;
        case"Chionidae":
            $db_name = "Sheathbills (Chionidae)";
            break;
        case"Ciconiidae":
            $db_name = "Storks (Ciconiidae)";
            break;
        case"Coccyzidae":
            $db_name = "New World Cuckoos (Coccyzidae)";
            break;
        case"Coliidae":
            $db_name = "Mousebirds (Coliidae)";
            break;
        case"Columbidae":
            $db_name = "Pigeons & Doves (Columbidae)";
            break;
        case"Coraciidae":
            $db_name = "Typical Rollers (Coraciidae)";
            break;
        case"Cracidae":
            $db_name = "Curassows & Guans (Cracidae)";
            break;
        case"Crotophagidae":
            $db_name = "Anis (Crotophagidae)";
            break;
        case"Cuculidae":
            $db_name = "Old World Cuckoo (Cuculidae)";
            break;
        case"Dendrocygnidae":
            $db_name = "Whistling Duck (Dendrocygnidae)";
            break;
        case"Diomedeidae":
            $db_name = "Albatrosses (Diomedeidae)";
            break;
        case"Dromadidae":
            $db_name = "Crab Plover (Dromadidae)";
            break;
        case"Dromaiidae":
            $db_name = "Emu (Dromaiidae)";
            break;
        case"Eurostopodidae":
            $db_name = "Eared Nighjars (Eurostopodidae)";
            break;
        case"Eurypygidae":
            $db_name = "Sunbittern (Eurypygidae)";
            break;
        case"Falconidae":
            $db_name = "Falcons & Caracaras (Falconidae)";
            break;
        case"Formicariidae":
            $db_name = "Ant Pitta (Formicariidae)";
            break;
        case"Fregatidae":
            $db_name = "Frigatebirds (Fregatidae)";
            break;
        case"Galbulidae":
            $db_name = "Jacamars (Galbulidae)";
            break;
        case"Gaviidae":
            $db_name = "Divers (Loons) (Gaviidae)";
            break;
        case"Glareolidae":
            $db_name = "Coursers & Pratincoles (Glareolidae)";
            break;
        case"Gruidae":
            $db_name = "Cranes (Gruidae)";
            break;
        case"Haematopodidae":
            $db_name = "Oystercatchers (Haematopodidae)";
            break;
        case"Heliornithidae":
            $db_name = "Sungrebe/Finfoots (Heliornithidae)";
            break;
        case"Hemiprocnidae":
            $db_name = "Treeswifts (Hemiprocnidae)";
            break;
        case"Hydrobatidae":
            $db_name = "Storm Petrels (Hydrobatidae)";
            break;
        case"Ibidorhynchidae":
            $db_name = "Ibisbill (Ibidorhynchidae)";
            break;
        case"Indicatoridae":
            $db_name = "Honeyguides (Indicatoridae)";
            break;
        case"Jacanidae":
            $db_name = "Ja�anas (Jacanidae)";
            break;
        case"Laridae":
            $db_name = "Gulls (Laridae)";
            break;
        case"Leptosomatidae":
            $db_name = "Cuckoo-Roller (Leptosomatidae)";
            break;
        case"Loriidae":
            $db_name = "Lorikeet (Loriidae)";
            break;
        case"Lybiidae":
            $db_name = "African Barbets (Lybiidae)";
            break;
        case"Megalimidae":
            $db_name = "Asian Barbets (Megalimidae)";
            break;
        case"Megapodiidae":
            $db_name = "Megapodes (Megapodiidae)";
            break;
        case"Meleagrididae":
            $db_name = "Turkeys (Meleagrididae)";
            break;
        case"Meropidae":
            $db_name = "Bee-eaters (Meropidae)";
            break;
        case"Mesitornithidae":
            $db_name = "Mesites (Mesitornithidae)";
            break;
        case"Momotidae":
            $db_name = "Motmots (Momotidae)";
            break;
        case"Musophagidae":
            $db_name = "Turacos & Allies (Musophagidae)";
            break;
        case"Neomorphidae":
            $db_name = "Ground Cuckoos (Neomorphidae)";
            break;
        case"nestoridae":
            $db_name = "New Zealand Parrots (Nestoridae)";
            break;
        case"Numididae":
            $db_name = "Guineafowls (Numididae)";
            break;
        case"Nyctibiidae":
            $db_name = "Potoos (Nyctibiidae)";
            break;
        case"Odontophoridae":
            $db_name = "New World Quails (Odontophoridae)";
            break;
        case"Opisthocomidae":
            $db_name = "Hoatzin (Opisthocomidae)";
            break;
        case"Otididae":
            $db_name = "Bustards (Otididae)";
            break;
        case"Pandionidae":
            $db_name = "Osprey (Pandionidae)";
            break;
        case"Pedionomidae":
            $db_name = "Plains-wanderer (Pedionomidae)";
            break;
        case"Pelecanidae":
            $db_name = "Pelicans (Pelecanidae)";
            break;
        case"Pelecanoididae":
            $db_name = "Diving-Petrels (Pelecanoididae)";
            break;
        case"Phaethontidae":
            $db_name = "Tropicbirds (Phaethontidae)";
            break;
        case"Phalacrocoracidae":
            $db_name = "Cormorants (Phalacrocoracidae)";
            break;
        case"Phasianidae":
            $db_name = "Pheasants & Partridges (Phasianidae)";
            break;
        case"Phoenicopteridae":
            $db_name = "Flamingos (Phoenicopteridae)";
            break;
        case"Phoeniculidae":
            $db_name = "Wood-Hoopoes/Scimitarbills (Phoeniculidae)";
            break;
        case"Picidae":
            $db_name = "Woodpeckers (Picidae)";
            break;
        case"pluvianellidae":
            $db_name = "Meganelic Pluver (Pluvianellidae)";
            break;
        case"pluvianidae":
            $db_name = "Egyptian Pluver (Pluvianidae)";
            break;
        case"Podargidae":
            $db_name = "Frogmouths (Podargidae)";
            break;
        case"Podicipedidae":
            $db_name = "Grebes (Podicipedidae)";
            break;
        case"Procellariidae":
            $db_name = "Petrels & Shearwaters (Procellariidae)";
            break;
        case"Psittacidae":
            $db_name = "Parrots (Psittacidae)";
            break;
        case"Psophiidae":
            $db_name = "Trumpeters (Psophiidae)";
            break;
        case"Psittacidae":
            $db_name = "Parrots (Psittacidae)";
            break;
        case"Psittaculidae":
            $db_name = "Old World Parrots (Psittaculidae)";
            break;
        case"Psophiidae":
            $db_name = "Trumpeters (Psophiidae)";
            break;
        case"Pteroclidae":
            $db_name = "Sandgrouse (Pteroclidae)";
            break;
        case"Rallidae":
            $db_name = "Rails (Rallidae)";
            break;
        case"Ramphastidae":
            $db_name = "Toucans (Ramphastidae)";
            break;
        case"Solitaire":
            $db_name = "Solitaire (Raphidae)";
            break;
        case"Recurvirostridae":
            $db_name = "Stilts & Avocet (Recurvirostridae)";
            break;
        case"Rheidae":
            $db_name = "Rheas (Rheidae)";
            break;
        case"Rhynochetidae":
            $db_name = "Kagu (Rhynochetidae)";
            break;
        case"Rostratulidae":
            $db_name = "Painted-snipe (Rostratulidae)";
            break;
        case"Rynchopidae":
            $db_name = "Skimmers (Rynchopidae)";
            break;
        case"Sagittariidae":
            $db_name = "Secretarybird (Sagittariidae)";
            break;
        case"Sarothruridae":
            $db_name = "Flufftails (Sarothruridae)";
            break;
        case"Scolopacidae":
            $db_name = "Sandpipers, Snipes & Allies (Scolopacidae)";
            break;
        case"Scopidae":
            $db_name = "Hamerkop (Scopidae)";
            break;
        case"Spheniscidae":
            $db_name = "Penguins (Spheniscidae)";
            break;
        case"Steatornithidae":
            $db_name = "Oilbird (Steatornithidae)";
            break;
        case"Stercorariidae":
            $db_name = "Jaegers & Skuas (Stercorariidae)";
            break;
        case"Sternidae":
            $db_name = "Terns (Sternidae)";
            break;
        case"Strigidae":
            $db_name = "Typical Owls (Strigidae)";
            break;
        case"Struthionidae":
            $db_name = "Ostriches (Struthionidae)";
            break;
        case"Sulidae":
            $db_name = "Boobies (Sulidae)";
            break;
        case"Tetraonidae":
            $db_name = "Grouse & Allies (Tetraonidae)";
            break;
        case"Thinocoridae":
            $db_name = "Seedsnipe (Thinocoridae)";
            break;
        case"Threskiornithidae":
            $db_name = "Ibises & Spoonbills (Threskiornithidae)";
            break;
        case"Tinamidae":
            $db_name = "Tinamous (Tinamidae)";
            break;
        case"Todidae":
            $db_name = "Todies (Todidae)";
            break;
        case"Trochilidae":
            $db_name = "Hummingbirds (Trochilidae)";
            break;
        case"Trogonidae":
            $db_name = "Trogons (Trogonidae)";
            break;
        case"Turnicidae":
            $db_name = "Buttonquails (Turnicidae)";
            break;
        case"Tytonidae":
            $db_name = "Barn & Grass Owls (Tytonidae)";
            break;
        case"Upupidae":
            $db_name = "Hoopoes (Upupidae)";
            break;
        case"species_and_families_homepage_non_passerines":
            $db_name = "Non-Passerines - General";
            break;
        case"acanthisittidae":
            $db_name = "New Zealand Wrens (Acanthisittidae)";
            break;
        case"acanthizidae":
            $db_name = "Australasian Warblers (Acanthizidae)";
            break;
        case"acrocephalidae":
            $db_name = "Reed warblers and allies (Acrocephalidae)";
            break;
        case"aegithalidae":
            $db_name = "Bushtits (Aegithalidae)";
            break;
        case"aegithinidae":
            $db_name = "Ioras (Aegithinidae)";
            break;
        case"alaudidae":
            $db_name = "Larks (Alaudidae)";
            break;
        case"artamidae":
            $db_name = "Woodswallows (Artamidae)";
            break;
        case"atrichornithidae":
            $db_name = "Scrubbirds (Atrichornithidae)";
            break;
        case"bernieridae":
            $db_name = "Malagasy warblers (Bernieridae)";
            break;
        case"bombycillidae":
            $db_name = "Waxwings (Bombycillidae)";
            break;
        case"buphagidae":
            $db_name = "Oxpeckers (Buphagidae)";
            break;
        case"calcariidae":
            $db_name = "Longspurs, snow buntings (Calcariidae)";
            break;
        case"callaeidae":
            $db_name = "Wattled Crows (Callaeidae)";
            break;
        case"campephagidae":
            $db_name = "Cuckooshrikes (Campephagidae)";
            break;
        case"cardinalidae":
            $db_name = "Grosbeaks, Saltators & Allies (Cardinalidae)";
            break;
        case"certhiidae":
            $db_name = "Treecreepers (Certhiidae)";
            break;
        case"cettidae":
            $db_name = "Cettia bush warblers and allies (Cettidae)";
            break;
        case"chaetopidae":
            $db_name = "Rockjumpers (Chaetopidae)";
            break;
        case"chloropseidae":
            $db_name = "Leafbirds (Chloropseidae)";
            break;
        case"cinclidae":
            $db_name = "Dippers (Cinclidae)";
            break;
        case"cisticolidae":
            $db_name = "Cisticolas and allies (Cisticolidae)";
            break;
        case"climacteridae":
            $db_name = "Australasian Treecreepers (Climacteridae)";
            break;
        case"cnemophilidae":
            $db_name = "Satinbirds (Cnemophilidae)";
            break;
        case"coerebidae":
            $db_name = "Bananaquit (Coerebidae)";
            break;
        case"conopophagidae":
            $db_name = "Gnateaters (Conopophagidae)";
            break;
        case"corcoracidae":
            $db_name = "Australian Mudnesters (Corcoracidae)";
            break;
        case"corvidae":
            $db_name = "Crows, Jays (Corvidae)";
            break;
        case"cotingidae":
            $db_name = "Cotingas (Cotingidae)";
            break;
        case"cracticidae":
            $db_name = "Butcherbirds and Allies (Cracticidae)";
            break;
        case"dasyornithidae":
            $db_name = "Bristlebirds (Dasyornithidae)";
            break;
        case"dicaeidae":
            $db_name = "Flowerpeckers (Dicaeidae)";
            break;
        case"dicruridae":
            $db_name = "Drongos (Dicruridae)";
            break;
        case"donacobiidae":
            $db_name = "Black-capped Donacobius (Donacobiidae)";
            break;
        case"dulidae":
            $db_name = "Palmchat (Dulidae)";
            break;
        case"emberizidae":
            $db_name = "Buntings, New World Sparrows & Allies (Emberizidae)";
            break;
        case"estrildidae":
            $db_name = "Waxbills, Munias & Allies (Estrildidae)";
            break;
        case"eupetidae":
            $db_name = "Rail-babbler (Eupetidae)";
            break;
        case"eurylaimidae":
            $db_name = "Broadbills (Eurylaimidae)";
            break;
        case"formicariidae":
            $db_name = "Antthrushes (Formicariidae)";
            break;
        case"fringillidae":
            $db_name = "Finches (Fringillidae)";
            break;
        case"furnariidae":
            $db_name = "Ovenbirds (Furnariidae)";
            break;
        case"grallariidae":
            $db_name = "Antpittas (Grallariidae)";
            break;
        case"hirundinidae":
            $db_name = "Swallows, martins (Hirundinidae)";
            break;
        case"hyliotidae":
            $db_name = "Hyliotas (Hyliotidae)";
            break;
        case"hylocitreidae":
            $db_name = "Hylocitrea (Hylocitreidae)";
            break;
        case"hypocoliidae":
            $db_name = "Hypocolius (Hypocoliidae)";
            break;
        case"icteridae":
            $db_name = "Oropendolas, Orioles & Blackbirds (Icteridae)";
            break;
        case"incertae":
            $db_name = "Sedis, Family Uncertain (Incertae)";
            break;
        case"irenidae":
            $db_name = "Fairy-bluebirds (Irenidae)";
            break;
        case"laniidae":
            $db_name = "Shrikes (Laniidae)";
            break;
        case"machaerirhynchidae":
            $db_name = "Boatbills (Machaerirhynchidae)";
            break;
        case"malaconotidae":
            $db_name = "Bushshrikes (Malaconotidae)";
            break;
        case"maluridae":
            $db_name = "Australasian Wrens (Maluridae)";
            break;
        case"megaluridae":
            $db_name = "Grassbirds and allies (Megaluridae)";
            break;
        case"melanocharitidae":
            $db_name = "Berrypeckers, longbills (Melanocharitidae)";
            break;
        case"melanopareiidae":
            $db_name = "Crescentchests (Melanopareiidae)";
            break;
        case"meliphagidae":
            $db_name = "Honeyeaters (Meliphagidae)";
            break;
        case"menuridae":
            $db_name = "Lyrebirds (Menuridae)";
            break;
        case"mimidae":
            $db_name = "Mockingbirds, Thrashers (Mimidae)";
            break;
        case"mohoidae":
            $db_name = "Oos (Mohoidae)";
            break;
        case"monarchidae":
            $db_name = "Monarchs (Monarchidae)";
            break;
        case"motacillidae":
            $db_name = "Wagtails, Pipits (Motacillidae)";
            break;
        case"muscicapidae":
            $db_name = "Chats, Old World Flycatchers (Muscicapidae)";
            break;
        case"nectariniidae":
            $db_name = "Sunbirds (Nectariniidae)";
            break;
        case"neosittidae":
            $db_name = "Sittellas (Neosittidae)";
            break;
        case"nicatoridae":
            $db_name = "Nicators (Nicatoridae)";
            break;
        case"notiomystidae":
            $db_name = "Stitchbird (Notiomystidae)";
            break;
        case"oriolidae":
            $db_name = "Figbirds, Orioles (Oriolidae)";
            break;
        case"orthonychidae":
            $db_name = "Logrunners (Orthonychidae)";
            break;
        case"oxyruncidae":
            $db_name = "Sharpbill (Oxyruncidae)";
            break;
        case"pachycephalidae":
            $db_name = "Whistlers and Allies (Pachycephalidae)";
            break;
        case"panuridae":
            $db_name = "Bearded Reedling (Panuridae)";
            break;
        case"paradisaeidae":
            $db_name = "Birds-of-paradise (Paradisaeidae)";
            break;
        case"paramythiidae":
            $db_name = "Painted Berrypeckers (Paramythiidae)";
            break;
        case"pardalotidae":
            $db_name = "Pardalotes (Pardalotidae)";
            break;
        case"paridae":
            $db_name = "Tits, chickadees (Paridae)";
            break;
        case"parulidae":
            $db_name = "New World Warblers (Parulidae)";
            break;
        case"passeridae":
            $db_name = "Old World Sparrows, Snowfinches (Passeridae)";
            break;
        case"petroicidae":
            $db_name = "Australasian Robins (Petroicidae)";
            break;
        case"peucedramidae":
            $db_name = "Olive Warbler (Peucedramidae)";
            break;
        case"phylloscopidae":
            $db_name = "Leaf warblers and allies (Phylloscopidae)";
            break;
        case"picathartidae":
            $db_name = "Rockfowl (Picathartidae)";
            break;
        case"pipridae":
            $db_name = "Manakins (Pipridae)";
            break;
        case"pityriaseidae":
            $db_name = "Bristlehead (Pityriaseidae)";
            break;
        case"pittidae":
            $db_name = "Pittas (Pittidae)";
            break;
        case"platysteiridae":
            $db_name = "Wattle-eyes, Batises (Platysteiridae)";
            break;
        case"ploceidae":
            $db_name = "Weavers, Widowbirds (Ploceidae)";
            break;
        case"polioptilidae":
            $db_name = "Gnatcatchers (Polioptilidae)";
            break;
        case"pomatostomidae":
            $db_name = "Australasian Babblers (Pomatostomidae)";
            break;
        case"prionopidae":
            $db_name = "Helmetshrikes (Prionopidae)";
            break;
        case"promeropidae":
            $db_name = "Sugarbirds and allies (Promeropidae)";
            break;
        case"prunellidae":
            $db_name = "Accentors (Prunellidae)";
            break;
        case"psophodidae":
            $db_name = "Whipbirds, Jewel-babblers, quail-thrushes (Psophodidae)";
            break;
        case"ptilogonatidae":
            $db_name = "Silky-flycatchers (Ptilogonatidae)";
            break;
        case"ptilonorhynchidae":
            $db_name = "Bowerbirds (Ptilonorhynchidae)";
            break;
        case"pycnonotidae":
            $db_name = "Bulbuls (Pycnonotidae)";
            break;
        case"regulidae":
            $db_name = "Goldcrests, kinglets (Regulidae)";
            break;
        case"remizidae":
            $db_name = "Penduline Tits (Remizidae)";
            break;
        case"rhabdornithidae":
            $db_name = "Philippine Creepers (Rhabdornithidae)";
            break;
        case"rhinocryptidae":
            $db_name = "Tapaculos (Rhinocryptidae)";
            break;
        case"rhipiduridae":
            $db_name = "Fantails (Rhipiduridae)";
            break;
        case"sittidae":
            $db_name = "Nuthatches (Sittidae)";
            break;
        case"stenostiridae":
            $db_name = "Fairy Flycatchers (Stenostiridae)";
            break;
        case"sturnidae":
            $db_name = "Starlings (Sturnidae)";
            break;
        case"sylviidae":
            $db_name = "Sylviid Babblers (Sylviidae)";
            break;
        case"tephrodornithidae":
            $db_name = "Woodshrikes and allies (Tephrodornithidae)";
            break;
        case"thamnophilidae":
            $db_name = "Antbirds (Thamnophilidae)";
            break;
        case"thraupidae":
            $db_name = "Tanagers and Allies (Thraupidae)";
            break;
        case"tichodromidae":
            $db_name = "Wallcreeper (Tichodromidae)";
            break;
        case"timaliidae":
            $db_name = "Babblers, Parrotbills (Timaliidae)";
            break;
        case"tityridae":
            $db_name = "Tityras, Becards (Tityridae)";
            break;
        case"troglodytidae":
            $db_name = "Wrens (Troglodytidae)";
            break;
        case"turdidae":
            $db_name = "Thrushes (Turdidae)";
            break;
        case"tyrannidae":
            $db_name = "Tyrant Flycatchers (Tyrannidae)";
            break;
        case"urocynchramidae":
            $db_name = "Przevalski's Finch (Urocynchramidae)";
            break;
        case"vangidae":
            $db_name = "Vangas (Vangidae)";
            break;
        case"viduidae":
            $db_name = "Indigobirds, Whydahs (Viduidae)";
            break;
        case"vireonidae":
            $db_name = "Vireos, Greenlets (Vireonidae)";
            break;
        case"zosteropidae":
            $db_name = "White-eyes (Zosteropidae)";
            break;
        case"species_and_families_homepage_passerines":
            $db_name = "Passerines - General";
            break;
        default:
            $db_name = str_replace("_", " ", $db_name);
    }
    return $db_name;
}

?>
<html>
<head>

<style type="text/css">
    <!--
    body {
        background-color: #317B84;
    }

    td {
        font-family: ms sans serif;
        font-size: 14pt;
        color: white;
    }

    /
    /
    -->
</style>


<script language="JavaScript">
<!--

rnd.today = new Date();
rnd.seed = rnd.today.getTime();

function rnd() {
    rnd.seed = (rnd.seed * 9301 + 49297) % 233280;
    return rnd.seed / (233280.0);
}

function rand(number) {
    return Math.ceil(rnd() * number);
}

function reshow(object) {
    subcategory = object.options[object.selectedIndex].value;
    if (subcategory == 'Select a category') {
        document.category.subcategory.focus();
    }
    else {
        if (msie) window.document.frames[0].location.href = 'what.htm';
        else {
            for (var i = document.region.names.length; i > 0; i--)
                document.region.names.options[0] = null;
            reloading = true;
            showlinks();
            document.region.names.options[0].selected = true;
        }
        return false;
    }
}

function load(object) {
    if (object.options[object.selectedIndex].value == 'Select a sub-category') {
        document.region.names.focus();
    }
    else {
        parent.home.location.href = 'pick_record.php?category=ornithology&subcategory=' + object.options[object.selectedIndex].value + '&random=1114618710' + rand(255);
        return false;
    }
}

function quickload(place) {
    parent.home.location.href = 'pick_record.php?category=ornithology&subcategory=' + place + '&random=1114618710' + rand(255);
}

function showlinks() {
    if (subcategory == 'Banding and ringing') {
        opt('', 'No further response required');
        quickload('banding_and_ringing');
    }
    if (subcategory == 'Bird Fairs, festivals, Conferences & Symposia') {
        opt('', 'No further response required');
        quickload('bird_fairs');
    }
    if (subcategory == 'Birding organisations') {
        opt('', 'No further response required');
        quickload('birding_organisations');
    }
    if (subcategory == 'Conservation') {
        opt('', 'No further response required');
        quickload('conservation');
    }
    if (subcategory == 'Courses') {
        opt('', 'No further response required');
        quickload('courses');
    }
    if (subcategory == 'Identification') {
        opt('', 'No further response required');
        quickload('identification');
    }
    if (subcategory == 'Migration') {
        opt('', 'No further response required');
        quickload('migration');
    }
    if (subcategory == 'Museums') {
        opt('', 'No further response required');
        quickload('museums');
    }
    if (subcategory == 'Names and taxonomy') {
        opt('', 'No further response required');
        quickload('names_and_taxonomy');
    }
    if (subcategory == 'Weather and tides') {
        opt('', 'No further response required');
        quickload('weather_and_tides');
    }
    if (subcategory == 'Pelagics') {
        opt('', 'No further response required');
        quickload('pelagics');
    }
    if (subcategory == 'Species and families - Non-Passerines') {
        opt('Select a sub-category', 'Select a sub-category');
        opt('Accipitridae', 'Hawks & Eagles (Accipitridae)');
        opt('Aegothelidae', 'Owlet-Nightjars (Aegothelidae)');
        opt('Alcedinidae', 'Kingfishers (Alcedinidae)');
        opt('Alcidae', 'Auks (Alcidae)');
        opt('Anatidae', 'Swans, Geese & Ducks (Anatidae)');
        opt('Anhimidae', 'Screamers (Anhimidae)');
        opt('Anhingidae', 'Anhinga & Darters (Anhingidae)');
        opt('Anseranatidae', 'Magpie Goose (Anseranatidae)');
        opt('Apodidae', 'Typical Swifts (Apodidae)');
        opt('Apterygidae', 'Kiwis (Apterygidae)');
        opt('Aramidae', 'Limpkin (Aramidae)');
        opt('Ardeidae', 'Herons (Ardeidae)');
        opt('Balaenicipitidae', 'Shoebill (Balaenicipitidae)');
        opt('Brachypteraciidae', 'Ground-Rollers (Brachypteraciidae)');
        opt('Bucconidae', 'Puffbirds (Bucconidae)');
        opt('Bucerotidae', 'Typical Hornbills (Bucerotidae)');
        opt('Bucorvidae', 'Ground-Hornbills (Bucorvidae)');
        opt('Burhinidae', 'Thick-knees (Burhinidae)');
        opt('Cacatuidae', 'Cockatoos (Cacatuidae)');
        opt('Capitonidae', 'New World Barbets (Capitonidae)');
        opt('Caprimulgidae', 'Nightjars (Caprimulgidae)');
        opt('Cariamidae', 'Seriemas (Cariamidae)');
        opt('Casuariidae', 'Cassowaries (Casuariidae)');
        opt('Cathartidae', 'New World Vultures (Cathartidae)');
        opt('Centropodidae', 'Coucal (Centropodidae)');
        opt('Charadriidae', 'Plovers (Charadriidae)');
        opt('Chionidae', 'Sheathbills (Chionidae)');
        opt('Ciconiidae', 'Storks (Ciconiidae)');
        opt('Coccyzidae', 'New World Cuckoos (Coccyzidae)');
        opt('Coliidae', 'Mousebirds (Coliidae)');
        opt('Columbidae', 'Pigeons & Doves (Columbidae)');
        opt('Coraciidae', 'Typical Rollers (Coraciidae)');
        opt('Cracidae', 'Curassows & Guans (Cracidae)');
        opt('Crotophagidae', 'Anis (Crotophagidae)');
        opt('Cuculidae', 'Old World Cuckoo (Cuculidae)');
        opt('Dendrocygnidae', 'Whistling Duck (Dendrocygnidae)');
        opt('Diomedeidae', 'Albatrosses (Diomedeidae)');
        opt('Dromadidae', 'Crab Plover (Dromadidae)');
        opt('Dromaiidae', 'Emu (Dromaiidae)');
        opt('Eurostopodidae', 'Eared Nighjars (Eurostopodidae)');
        opt('Eurypygidae', 'Sunbittern (Eurypygidae)');
        opt('Falconidae', 'Falcons & Caracaras (Falconidae)');
        opt('Formicariidae', 'Ant Pitta (Formicariidae)');
        opt('Fregatidae', 'Frigatebirds (Fregatidae)');
        opt('Galbulidae', 'Jacamars (Galbulidae)');
        opt('Gaviidae', 'Divers (Loons) (Gaviidae)');
        opt('Glareolidae', 'Coursers & Pratincoles (Glareolidae)');
        opt('Gruidae', 'Cranes (Gruidae)');
        opt('Haematopodidae', 'Oystercatchers (Haematopodidae)');
        opt('Heliornithidae', 'Sungrebe/Finfoots (Heliornithidae)');
        opt('Hemiprocnidae', 'Treeswifts (Hemiprocnidae)');
        opt('Hydrobatidae', 'Storm Petrels (Hydrobatidae)');
        opt('Ibidorhynchidae', 'Ibisbill (Ibidorhynchidae)');
        opt('Indicatoridae', 'Honeyguides (Indicatoridae)');
        opt('Jacanidae', 'Ja�anas (Jacanidae)');
        opt('Laridae', 'Gulls (Laridae)');
        opt('Leptosomatidae', 'Cuckoo-Roller (Leptosomatidae)');
        opt('Loriidae', 'Lorikeet (Loriidae)');
        opt('Lybiidae', 'African Barbets (Lybiidae)');
        opt('Megalimidae', 'Asian Barbets (Megalimidae)');
        opt('Megapodiidae', 'Megapodes (Megapodiidae)');
        opt('Meleagrididae', 'Turkeys (Meleagrididae)');
        opt('Meropidae', 'Bee-eaters (Meropidae)');
        opt('Mesitornithidae', 'Mesites (Mesitornithidae)');
        opt('Momotidae', 'Motmots (Momotidae)');
        opt('Musophagidae', 'Turacos & Allies (Musophagidae)');
        opt('Neomorphidae', 'Ground Cuckoos (Neomorphidae)');
        opt('nestoridae', 'New Zealand Parrots (Nestoridae)');
        opt('Numididae', 'Guineafowls (Numididae)');
        opt('Nyctibiidae', 'Potoos (Nyctibiidae)');
        opt('Oceanitidae', 'Austral Storm Petrels (Oceanitidae)');
        opt('Odontophoridae', 'New World Quails (Odontophoridae)');
        opt('Opisthocomidae', 'Hoatzin (Opisthocomidae)');
        opt('Otididae', 'Bustards (Otididae)');
        opt('Pandionidae', 'Osprey (Pandionidae)');
        opt('Pedionomidae', 'Plains-wanderer (Pedionomidae)');
        opt('Pelecanidae', 'Pelicans (Pelecanidae)');
        opt('Phaethontidae', 'Tropicbirds (Phaethontidae)');
        opt('Phalacrocoracidae', 'Cormorants (Phalacrocoracidae)');
        opt('Phasianidae', 'Pheasants & Partridges (Phasianidae)');
        opt('Phoenicopteridae', 'Flamingos (Phoenicopteridae)');
        opt('Phoeniculidae', 'Wood-Hoopoes/Scimitarbills (Phoeniculidae)');
        opt('Picidae', 'Woodpeckers (Picidae)');
        opt('pluvianellidae', 'Magellanic Plover (Pluvianellidae)');
        opt('pluvianidae', 'Egyptian Plover (Pluvianidae)');
        opt('Podargidae', 'Frogmouths (Podargidae)');
        opt('Podicipedidae', 'Grebes (Podicipedidae)');
        opt('Procellariidae', 'Petrels & Shearwaters (Procellariidae)');
        opt('Psittacidae', 'Parrots (Psittacidae)');
        opt('Psittaculidae', 'Old World Parrots (Psittaculidae)');
        opt('Psophiidae', 'Trumpeters (Psophiidae)');
        opt('Pteroclidae', 'Sandgrouse (Pteroclidae)');
        opt('Rallidae', 'Rails (Rallidae)');
        opt('Ramphastidae', 'Toucans (Ramphastidae)');
        opt('Solitaire', 'Solitaire (Raphidae)');
        opt('Recurvirostridae', 'Stilts & Avocet (Recurvirostridae)');
        opt('Rheidae', 'Rheas (Rheidae)');
        opt('Rhynochetidae', 'Kagu (Rhynochetidae)');
        opt('Rostratulidae', 'Painted-snipe (Rostratulidae)');
        opt('Rynchopidae', 'Skimmers (Rynchopidae)');
        opt('Sagittariidae', 'Secretarybird (Sagittariidae)');
        opt('Sarothruridae', 'Flufftails (Sarothruridae)');
        opt('Scolopacidae', 'Sandpipers, Snipes & Allies (Scolopacidae)');
        opt('Scopidae', 'Hamerkop (Scopidae)');
        opt('Spheniscidae', 'Penguins (Spheniscidae)');
        opt('Steatornithidae', 'Oilbird (Steatornithidae)');
        opt('Stercorariidae', 'Jaegers & Skuas (Stercorariidae)');
        opt('Sternidae', 'Terns (Sternidae)');
        opt('Strigidae', 'Typical Owls (Strigidae)');
        opt('Struthionidae', 'Ostriches (Struthionidae)');
        opt('Sulidae', 'Boobies (Sulidae)');
        opt('Tetraonidae', 'Grouse & Allies (Tetraonidae)');
        opt('Thinocoridae', 'Seedsnipe (Thinocoridae)');
        opt('Threskiornithidae', 'Ibises & Spoonbills (Threskiornithidae)');
        opt('Tinamidae', 'Tinamous (Tinamidae)');
        opt('Todidae', 'Todies (Todidae)');
        opt('Trochilidae', 'Hummingbirds (Trochilidae)');
        opt('Trogonidae', 'Trogons (Trogonidae)');
        opt('Turnicidae', 'Buttonquails (Turnicidae)');
        opt('Tytonidae', 'Barn & Grass Owls (Tytonidae)');
        opt('Upupidae', 'Hoopoes (Upupidae)');
        opt('Select a sub-category', '---------- General Pages ----------');
        opt('species_and_families_homepage_non_passerines', 'Non-Passerines - General');
    }
    if (subcategory == 'Species and families - Passerines') {
        opt('Select a sub-category', 'Select a sub-category');
        opt('acanthisittidae', 'New Zealand Wrens (Acanthisittidae)');
        opt('acanthizidae', 'Australasian Warblers (Acanthizidae)');
        opt('acrocephalidae', 'Reed warblers and allies (Acrocephalidae)');
        opt('aegithalidae', 'Bushtits (Aegithalidae)');
        opt('aegithinidae', 'Ioras (Aegithinidae)');
        opt('alaudidae', 'Larks (Alaudidae)');
        opt('artamidae', 'Woodswallows (Artamidae)');
        opt('atrichornithidae', 'Scrubbirds (Atrichornithidae)');
        opt('bernieridae', 'Malagasy warblers (Bernieridae)');
        opt('bombycillidae', 'Waxwings (Bombycillidae)');
        opt('buphagidae', 'Oxpeckers (Buphagidae)');
        opt('calcariidae', 'Longspurs, snow buntings (Calcariidae)');
        opt('callaeidae', 'Wattled Crows (Callaeidae)');
        opt('campephagidae', 'Cuckooshrikes (Campephagidae)');
        opt('cardinalidae', 'Grosbeaks, Saltators & Allies (Cardinalidae)');
        opt('certhiidae', 'Treecreepers (Certhiidae)');
        opt('cettidae', 'Cettia bush warblers and allies (Cettidae)');
        opt('chaetopidae', 'Rockjumpers (Chaetopidae)');
        opt('chloropseidae', 'Leafbirds (Chloropseidae)');
        opt('cinclidae', 'Dippers (Cinclidae)');
        opt('cisticolidae', 'Cisticolas and allies (Cisticolidae)');
        opt('climacteridae', 'Australasian Treecreepers (Climacteridae)');
        opt('cnemophilidae', 'Satinbirds (Cnemophilidae)');
        opt('coerebidae', 'Bananaquit (Coerebidae)');
        opt('conopophagidae', 'Gnateaters (Conopophagidae)');
        opt('corcoracidae', 'Australian Mudnesters (Corcoracidae)');
        opt('corvidae', 'Crows, Jays (Corvidae)');
        opt('cotingidae', 'Cotingas (Cotingidae)');
        opt('cracticidae', 'Butcherbirds and Allies (Cracticidae)');
        opt('dasyornithidae', 'Bristlebirds (Dasyornithidae)');
        opt('dicaeidae', 'Flowerpeckers (Dicaeidae)');
        opt('dicruridae', 'Drongos (Dicruridae)');
        opt('donacobiidae', 'Black-capped Donacobius (Donacobiidae)');
        opt('dulidae', 'Palmchat (Dulidae)');
        opt('emberizidae', 'Buntings, New World Sparrows & Allies (Emberizidae)');
        opt('estrildidae', 'Waxbills, Munias & Allies (Estrildidae)');
        opt('eupetidae', 'Rail-babbler (Eupetidae)');
        opt('eurylaimidae', 'Broadbills (Eurylaimidae)');
        opt('formicariidae', 'Antthrushes (Formicariidae)');
        opt('fringillidae', 'Finches (Fringillidae)');
        opt('furnariidae', 'Ovenbirds (Furnariidae)');
        opt('grallariidae', 'Antpittas (Grallariidae)');
        opt('hirundinidae', 'Swallows, martins (Hirundinidae)');
        opt('hyliotidae', 'Hyliotas (Hyliotidae)');
        opt('hylocitreidae', 'Hylocitrea (Hylocitreidae)');
        opt('hypocoliidae', 'Hypocolius (Hypocoliidae)');
        opt('icteridae', 'Oropendolas, Orioles & Blackbirds (Icteridae)');
        opt('incertae', 'Sedis, Family Uncertain (Incertae)');
        opt('irenidae', 'Fairy-bluebirds (Irenidae)');
        opt('laniidae', 'Shrikes (Laniidae)');
        opt('leiothrichidae', 'Laughing Thrushes (Leiothrichidae)');
        opt('machaerirhynchidae', 'Boatbills (Machaerirhynchidae)');
        opt('malaconotidae', 'Bushshrikes (Malaconotidae)');
        opt('maluridae', 'Australasian Wrens (Maluridae)');
        opt('megaluridae', 'Grassbirds and allies (Megaluridae)');
        opt('melanocharitidae', 'Berrypeckers, longbills (Melanocharitidae)');
        opt('melanopareiidae', 'Crescentchests (Melanopareiidae)');
        opt('meliphagidae', 'Honeyeaters (Meliphagidae)');
        opt('menuridae', 'Lyrebirds (Menuridae)');
        opt('mimidae', 'Mockingbirds, Thrashers (Mimidae)');
        opt('mohoidae', 'Oos (Mohoidae)');
        opt('monarchidae', 'Monarchs (Monarchidae)');
        opt('motacillidae', 'Wagtails, Pipits (Motacillidae)');
        opt('muscicapidae', 'Chats, Old World Flycatchers (Muscicapidae)');
        opt('nectariniidae', 'Sunbirds (Nectariniidae)');
        opt('neosittidae', 'Sittellas (Neosittidae)');
        opt('nicatoridae', 'Nicators (Nicatoridae)');
        opt('notiomystidae', 'Stitchbird (Notiomystidae)');
        opt('oriolidae', 'Figbirds, Orioles (Oriolidae)');
        opt('orthonychidae', 'Logrunners (Orthonychidae)');
        opt('oxyruncidae', 'Sharpbill (Oxyruncidae)');
        opt('pachycephalidae', 'Whistlers and Allies (Pachycephalidae)');
        opt('panuridae', 'Bearded Reedling (Panuridae)');
        opt('paradisaeidae', 'Birds-of-paradise (Paradisaeidae)');
        opt('paramythiidae', 'Painted Berrypeckers (Paramythiidae)');
        opt('pardalotidae', 'Pardalotes (Pardalotidae)');
        opt('paridae', 'Tits, chickadees (Paridae)');
        opt('parulidae', 'New World Warblers (Parulidae)');
        opt('passeridae', 'Old World Sparrows, Snowfinches (Passeridae)');
        opt('pellornidae', 'Fulvettas, Ground Babblers (Pellornidae)');
        opt('petroicidae', 'Australasian Robins (Petroicidae)');
        opt('peucedramidae', 'Olive Warbler (Peucedramidae)');
        opt('phylloscopidae', 'Leaf warblers and allies (Phylloscopidae)');
        opt('picathartidae', 'Rockfowl (Picathartidae)');
        opt('pipridae', 'Manakins (Pipridae)');
        opt('pityriaseidae', 'Bristlehead (Pityriaseidae)');
        opt('pittidae', 'Pittas (Pittidae)');
        opt('platysteiridae', 'Wattle-eyes, Batises (Platysteiridae)');
        opt('ploceidae', 'Weavers, Widowbirds (Ploceidae)');
        opt('polioptilidae', 'Gnatcatchers (Polioptilidae)');
        opt('pomatostomidae', 'Australasian Babblers (Pomatostomidae)');
        opt('prionopidae', 'Helmetshrikes (Prionopidae)');
        opt('promeropidae', 'Sugarbirds and allies (Promeropidae)');
        opt('prunellidae', 'Accentors (Prunellidae)');
        opt('psophodidae', 'Whipbirds, Jewel-babblers, quail-thrushes (Psophodidae)');
        opt('ptilogonatidae', 'Silky-flycatchers (Ptilogonatidae)');
        opt('ptilonorhynchidae', 'Bowerbirds (Ptilonorhynchidae)');
        opt('pycnonotidae', 'Bulbuls (Pycnonotidae)');
        opt('regulidae', 'Goldcrests, kinglets (Regulidae)');
        opt('remizidae', 'Penduline Tits (Remizidae)');
        opt('rhabdornithidae', 'Philippine Creepers (Rhabdornithidae)');
        opt('rhinocryptidae', 'Tapaculos (Rhinocryptidae)');
        opt('rhipiduridae', 'Fantails (Rhipiduridae)');
        opt('sittidae', 'Nuthatches (Sittidae)');
        opt('stenostiridae', 'Fairy Flycatchers (Stenostiridae)');
        opt('sturnidae', 'Starlings (Sturnidae)');
        opt('sylviidae', 'Sylviid Babblers (Sylviidae)');
        opt('tephrodornithidae', 'Woodshrikes and allies (Tephrodornithidae)');
        opt('thamnophilidae', 'Antbirds (Thamnophilidae)');
        opt('thraupidae', 'Tanagers and Allies (Thraupidae)');
        opt('tichodromidae', 'Wallcreeper (Tichodromidae)');
        opt('timaliidae', 'Babblers, Parrotbills (Timaliidae)');
        opt('tityridae', 'Tityras, Becards (Tityridae)');
        opt('troglodytidae', 'Wrens (Troglodytidae)');
        opt('turdidae', 'Thrushes (Turdidae)');
        opt('tyrannidae', 'Tyrant Flycatchers (Tyrannidae)');
        opt('urocynchramidae', 'Przevalskis Finch (Urocynchramidae)');
        opt('vangidae', 'Vangas (Vangidae)');
        opt('viduidae', 'Indigobirds, Whydahs (Viduidae)');
        opt('vireonidae', 'Vireos, Greenlets (Vireonidae)');
        opt('zosteropidae', 'White-eyes (Zosteropidae)');
        opt('Select a sub-category', ' ');
        opt('Select a sub-category', '---------- General Pages ----------');
        opt('species_and_families_homepage_passerines', 'Passerines - General');
    }
    if (subcategory == 'Study and behaviour') {
        opt('', 'No further response required');
        quickload('study_and_behaviour');
    }
    if (subcategory == 'Weather and tides') {
        opt('', 'No further response required');
        quickload('weather_and_tides');
    }
    if (subcategory != 'Select a category' && subcategory != '') {
        opt('', 'No further response required');
        quickload(subcategory);
    }
}

function opt(href, text) {
    if (reloading) {
        var optionName = new Option(text, href, false, false)
        var length = document.region.names.length;
        document.region.names.options[length] = optionName;
    }
    else
        document.write('<option value="', href, '">', text, '</option>');

}
//-->
</script>
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
<table border="0" cellpadding="0" cellspacing="5">
    <tr>
        <td valign="top"><font face="Verdana,Arial" size="4">&nbsp;Ornithology></font></td>
        <td>
            <form name="category">
                <select name="subcategory" onChange="return reshow(document.category.subcategory)">
                    <option selected>Select a category</option>
                    <?php

                    $category = "ornithology";

                    $tables = array();
                    $tables[1] = "introduction";
                    $tables[2] = "links_contributor";
                    $tables[3] = "links_county_recorder";
                    $tables[4] = "links_number_species";
                    $tables[5] = "links_endemics";
                    $tables[6] = "links_festivals";
                    $tables[7] = "links_mailing_lists";
                    $tables[8] = "links_museums";
                    $tables[9] = "links_observatories";
                    $tables[10] = "links_organisations";
                    $tables[11] = "links_places_to_stay";
                    $tables[12] = "links";
                    $tables[13] = "links_artists_photographers";
                    $tables[14] = "links_top_sites";
                    $tables[15] = "links_useful_reading";
                    $tables[16] = "links_useful_information";
                    $tables[17] = "links_reserves";
                    $tables[18] = "links_trip_reports";
                    $tables[19] = "links_holiday_companies";
                    $tables[20] = "links_banners";
                    $tables[21] = "links_family_links";
                    $tables[22] = "links_species_links";
                    $tables[23] = "sublinks";

                    $sub_category = array();
                    $sub_category_cnt = 0;

                    for ($i = 1; $i <= 23; $i++) {

                        $sql = "SELECT DISTINCT `sub-category` FROM " . $tables[$i] . " WHERE `category` = '" . $category . "' ORDER BY `sub-category` ASC";
                        //echo $sql;
                        $result = mysql_query($sql);
                        while ($row = mysql_fetch_assoc($result)) {

                            //echo $row["sub-category"];

                            if (!(in_array($row["sub-category"], $sub_category))) {
                                $sub_category[$sub_category_cnt] = $row["sub-category"];
                                //echo "<option value=\"$sub_category[$sub_category_cnt]\">".alt_name($sub_category[$sub_category_cnt])."</option>";
                                $sub_category_cnt++;
                            }
                        }

                        //echo $sub_category[$sub_category_cnt];

                    }

                    //sort the array into alphabetical order
                    sort($sub_category);

                    for ($i = 0; $i < count($sub_category); $i++) {
                        echo "<option value=\"$sub_category[$i]\">" . alt_name($sub_category[$i]) . "</option>";
                    }
                    ?>
                </select>
            </form>
        </td>
        <td valign="top">&gt;</td>
        <td>
            <script language="JavaScript">
                <!--
                var reloading = false;
                var subcategory = document.category.subcategory.options[0].text;

                if (navigator.appVersion.indexOf('MSIE 3') != -1) var msie = true; else var msie = false;

                if (msie) {
                    document.write('<IFRAME FRAMEBORDER=0 SCROLLING=NO SRC="what.htm" WIDTH="100%" HEIGHT="100">');
                    document.write('</IFRAME>');
                }
                else {
                    document.write('<form name="region">');
                    document.write('<select name="names" onChange="return load(document.region.names)">');
                    showlinks();
                    document.write('</select>');
                    document.write('</form>');
                }
                //-->
            </script>
        </td>
    </tr>
</table>

</body>
</html>