<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ElencoNazioniTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('elenco_nazioni')->delete();

        \DB::table('elenco_nazioni')->insert(array(
            0 =>
                array(
                    'alpha2' => 'AD',
                    'alpha3' => 'AND',
                    'langEN' => 'Andorra',
                    'langIT' => 'Andorra',
                    'nazionalitaEN' => 'Andorran',
                    'nazionalitaIT' => 'Andorra',
                    'bandiera' => 'andorra.svg',
                ),
            1 =>
                array(
                    'alpha2' => 'AE',
                    'alpha3' => 'ARE',
                    'langEN' => 'United Arab Emirates',
                    'langIT' => 'Emirati Arabi Uniti',
                    'nazionalitaEN' => 'Emirati, Emirian, Emiri',
                    'nazionalitaIT' => 'Emirati, Emirati Arabi, Emirati Arabi Uniti',
                    'bandiera' => 'united-arab-emirates.svg',
                ),
            2 =>
                array(
                    'alpha2' => 'AF',
                    'alpha3' => 'AFG',
                    'langEN' => 'Afghanistan',
                    'langIT' => 'Afghanistan',
                    'nazionalitaEN' => 'Afghan',
                    'nazionalitaIT' => 'Afgano',
                    'bandiera' => 'afghanistan.svg',
                ),
            3 =>
                array(
                    'alpha2' => 'AG',
                    'alpha3' => 'ATG',
                    'langEN' => 'Antigua and Barbuda',
                    'langIT' => 'Antigua e Barbuda',
                    'nazionalitaEN' => 'Antiguan or Barbudan',
                    'nazionalitaIT' => 'Antigua e Barbudan',
                    'bandiera' => 'antigua-and-barbuda.svg',
                ),
            4 =>
                array(
                    'alpha2' => 'AI',
                    'alpha3' => 'AIA',
                    'langEN' => 'Anguilla',
                    'langIT' => 'Anguilla',
                    'nazionalitaEN' => 'Anguillan',
                    'nazionalitaIT' => 'Anguillan',
                    'bandiera' => 'anguilla.svg',
                ),
            5 =>
                array(
                    'alpha2' => 'AL',
                    'alpha3' => 'ALB',
                    'langEN' => 'Albania',
                    'langIT' => 'Albania',
                    'nazionalitaEN' => 'Albanian',
                    'nazionalitaIT' => 'Albanese',
                    'bandiera' => 'albania.svg',
                ),
            6 =>
                array(
                    'alpha2' => 'AM',
                    'alpha3' => 'ARM',
                    'langEN' => 'Armenia',
                    'langIT' => 'Armenia',
                    'nazionalitaEN' => 'Armenian',
                    'nazionalitaIT' => 'Armeno',
                    'bandiera' => 'armenia.svg',
                ),
            7 =>
                array(
                    'alpha2' => 'AN',
                    'alpha3' => 'ANT',
                    'langEN' => 'Netherlands Antilles',
                    'langIT' => 'Antille Olandesi',
                    'nazionalitaEN' => NULL,
                    'nazionalitaIT' => NULL,
                    'bandiera' => 'bonaire.svg',
                ),
            8 =>
                array(
                    'alpha2' => 'AO',
                    'alpha3' => 'AGO',
                    'langEN' => 'Angola',
                    'langIT' => 'Angola',
                    'nazionalitaEN' => 'Angolan',
                    'nazionalitaIT' => 'Angolano',
                    'bandiera' => 'angola.svg',
                ),
            9 =>
                array(
                    'alpha2' => 'AQ',
                    'alpha3' => 'ATA',
                    'langEN' => 'Antarctica',
                    'langIT' => 'Antartide',
                    'nazionalitaEN' => 'Antarctic',
                    'nazionalitaIT' => 'Antartico',
                    'bandiera' => 'flag.svg',
                ),
            10 =>
                array(
                    'alpha2' => 'AR',
                    'alpha3' => 'ARG',
                    'langEN' => 'Argentina',
                    'langIT' => 'Argentina',
                    'nazionalitaEN' => 'Argentine',
                    'nazionalitaIT' => 'Argentino',
                    'bandiera' => 'argentina.svg',
                ),
            11 =>
                array(
                    'alpha2' => 'AS',
                    'alpha3' => 'ASM',
                    'langEN' => 'American Samoa',
                    'langIT' => 'Samoa Americane',
                    'nazionalitaEN' => 'American Samoan',
                    'nazionalitaIT' => 'Samoano americano',
                    'bandiera' => 'american-samoa.svg',
                ),
            12 =>
                array(
                    'alpha2' => 'AT',
                    'alpha3' => 'AUT',
                    'langEN' => 'Austria',
                    'langIT' => 'Austria',
                    'nazionalitaEN' => 'Austrian',
                    'nazionalitaIT' => 'Austriaco',
                    'bandiera' => 'austria.svg',
                ),
            13 =>
                array(
                    'alpha2' => 'AU',
                    'alpha3' => 'AUS',
                    'langEN' => 'Australia',
                    'langIT' => 'Australia',
                    'nazionalitaEN' => 'Australian',
                    'nazionalitaIT' => 'Australiano',
                    'bandiera' => 'australia.svg',
                ),
            14 =>
                array(
                    'alpha2' => 'AW',
                    'alpha3' => 'ABW',
                    'langEN' => 'Aruba',
                    'langIT' => 'Aruba',
                    'nazionalitaEN' => 'Aruban',
                    'nazionalitaIT' => 'Aruba',
                    'bandiera' => 'aruba.svg',
                ),
            15 =>
                array(
                    'alpha2' => 'AX',
                    'alpha3' => 'ALA',
                    'langEN' => 'Åland Islands',
                    'langIT' => 'Åland Islands',
                    'nazionalitaEN' => 'Åland Island',
                    'nazionalitaIT' => 'Land Island',
                    'bandiera' => 'aland-islands.svg',
                ),
            16 =>
                array(
                    'alpha2' => 'AZ',
                    'alpha3' => 'AZE',
                    'langEN' => 'Azerbaijan',
                    'langIT' => 'Azerbaijan',
                    'nazionalitaEN' => 'Azerbaijani, Azeri',
                    'nazionalitaIT' => 'Azero, azero',
                    'bandiera' => 'azerbaijan.svg',
                ),
            17 =>
                array(
                    'alpha2' => 'BA',
                    'alpha3' => 'BIH',
                    'langEN' => 'Bosnia and Herzegovina',
                    'langIT' => 'Bosnia Erzegovina',
                    'nazionalitaEN' => 'Bosnian or Herzegovinian',
                    'nazionalitaIT' => 'Bosniaco ed Erzegovina',
                    'bandiera' => 'bosnia-and-herzegovina.svg',
                ),
            18 =>
                array(
                    'alpha2' => 'BB',
                    'alpha3' => 'BRB',
                    'langEN' => 'Barbados',
                    'langIT' => 'Barbados',
                    'nazionalitaEN' => 'Barbadian',
                    'nazionalitaIT' => 'Barbados',
                    'bandiera' => 'barbados.svg',
                ),
            19 =>
                array(
                    'alpha2' => 'BD',
                    'alpha3' => 'BGD',
                    'langEN' => 'Bangladesh',
                    'langIT' => 'Bangladesh',
                    'nazionalitaEN' => 'Bangladeshi',
                    'nazionalitaIT' => 'Bengalese',
                    'bandiera' => 'bangladesh.svg',
                ),
            20 =>
                array(
                    'alpha2' => 'BE',
                    'alpha3' => 'BEL',
                    'langEN' => 'Belgium',
                    'langIT' => 'Belgio',
                    'nazionalitaEN' => 'Belgian',
                    'nazionalitaIT' => 'Belga',
                    'bandiera' => 'belgium.svg',
                ),
            21 =>
                array(
                    'alpha2' => 'BF',
                    'alpha3' => 'BFA',
                    'langEN' => 'Burkina Faso',
                    'langIT' => 'Burkina Faso',
                    'nazionalitaEN' => 'Burkinabé',
                    'nazionalitaIT' => 'Burkinabé',
                    'bandiera' => 'burkina-faso.svg',
                ),
            22 =>
                array(
                    'alpha2' => 'BG',
                    'alpha3' => 'BGR',
                    'langEN' => 'Bulgaria',
                    'langIT' => 'Bulgaria',
                    'nazionalitaEN' => 'Bulgarian',
                    'nazionalitaIT' => 'Bulgaro',
                    'bandiera' => 'bulgaria.svg',
                ),
            23 =>
                array(
                    'alpha2' => 'BH',
                    'alpha3' => 'BHR',
                    'langEN' => 'Bahrain',
                    'langIT' => 'Bahrain',
                    'nazionalitaEN' => 'Bahraini',
                    'nazionalitaIT' => 'Bahrein',
                    'bandiera' => 'bahrain.svg',
                ),
            24 =>
                array(
                    'alpha2' => 'BI',
                    'alpha3' => 'BDI',
                    'langEN' => 'Burundi',
                    'langIT' => 'Burundi',
                    'nazionalitaEN' => 'Burundian',
                    'nazionalitaIT' => 'Burundi',
                    'bandiera' => 'burundi.svg',
                ),
            25 =>
                array(
                    'alpha2' => 'BJ',
                    'alpha3' => 'BEN',
                    'langEN' => 'Benin',
                    'langIT' => 'Benin',
                    'nazionalitaEN' => 'Beninese, Beninois',
                    'nazionalitaIT' => 'Beninese',
                    'bandiera' => 'benin.svg',
                ),
            26 =>
                array(
                    'alpha2' => 'BM',
                    'alpha3' => 'BMU',
                    'langEN' => 'Bermuda',
                    'langIT' => 'Bermuda',
                    'nazionalitaEN' => 'Bermudian, Bermudan',
                    'nazionalitaIT' => 'Bermuda, Bermuda',
                    'bandiera' => 'bermuda.svg',
                ),
            27 =>
                array(
                    'alpha2' => 'BN',
                    'alpha3' => 'BRN',
                    'langEN' => 'Brunei Darussalam',
                    'langIT' => 'Brunei Darussalam',
                    'nazionalitaEN' => 'Bruneian',
                    'nazionalitaIT' => 'Brunei',
                    'bandiera' => 'brunei.svg',
                ),
            28 =>
                array(
                    'alpha2' => 'BO',
                    'alpha3' => 'BOL',
                    'langEN' => 'Bolivia',
                    'langIT' => 'Bolivia',
                    'nazionalitaEN' => 'Bolivian',
                    'nazionalitaIT' => 'Boliviano',
                    'bandiera' => 'bolivia.svg',
                ),
            29 =>
                array(
                    'alpha2' => 'BR',
                    'alpha3' => 'BRA',
                    'langEN' => 'Brazil',
                    'langIT' => 'Brasile',
                    'nazionalitaEN' => 'Brazilian',
                    'nazionalitaIT' => 'Brasiliano',
                    'bandiera' => 'brazil.svg',
                ),
            30 =>
                array(
                    'alpha2' => 'BS',
                    'alpha3' => 'BHS',
                    'langEN' => 'Bahamas',
                    'langIT' => 'Bahamas',
                    'nazionalitaEN' => 'Bahamian',
                    'nazionalitaIT' => 'Bahamas',
                    'bandiera' => 'bahamas.svg',
                ),
            31 =>
                array(
                    'alpha2' => 'BT',
                    'alpha3' => 'BTN',
                    'langEN' => 'Bhutan',
                    'langIT' => 'Bhutan',
                    'nazionalitaEN' => 'Bhutanese',
                    'nazionalitaIT' => 'Bhutanesi',
                    'bandiera' => 'bhutan.svg',
                ),
            32 =>
                array(
                    'alpha2' => 'BV',
                    'alpha3' => 'BVT',
                    'langEN' => 'Bouvet Island',
                    'langIT' => 'Isola di Bouvet',
                    'nazionalitaEN' => 'Bouvet Island',
                    'nazionalitaIT' => 'Isola Bouvet',
                    'bandiera' => 'flag.svg',
                ),
            33 =>
                array(
                    'alpha2' => 'BW',
                    'alpha3' => 'BWA',
                    'langEN' => 'Botswana',
                    'langIT' => 'Botswana',
                    'nazionalitaEN' => 'Motswana, Botswanan',
                    'nazionalitaIT' => 'Motswana, Botswana',
                    'bandiera' => 'botswana.svg',
                ),
            34 =>
                array(
                    'alpha2' => 'BY',
                    'alpha3' => 'BLR',
                    'langEN' => 'Belarus',
                    'langIT' => 'Bielorussia',
                    'nazionalitaEN' => 'Belarusian',
                    'nazionalitaIT' => 'Bielorusso',
                    'bandiera' => 'belarus.svg',
                ),
            35 =>
                array(
                    'alpha2' => 'BZ',
                    'alpha3' => 'BLZ',
                    'langEN' => 'Belize',
                    'langIT' => 'Belize',
                    'nazionalitaEN' => 'Belizean',
                    'nazionalitaIT' => 'Belize',
                    'bandiera' => 'belize.svg',
                ),
            36 =>
                array(
                    'alpha2' => 'CA',
                    'alpha3' => 'CAN',
                    'langEN' => 'Canada',
                    'langIT' => 'Canada',
                    'nazionalitaEN' => 'Canadian',
                    'nazionalitaIT' => 'Canadese',
                    'bandiera' => 'canada.svg',
                ),
            37 =>
                array(
                    'alpha2' => 'CC',
                    'alpha3' => 'CCK',
                    'langEN' => 'Cocos (Keeling) Islands',
                    'langIT' => 'Isole Cocos',
                    'nazionalitaEN' => 'Cocos Island',
                    'nazionalitaIT' => 'Isola di Cocos',
                    'bandiera' => 'cocos-island.svg',
                ),
            38 =>
                array(
                    'alpha2' => 'CD',
                    'alpha3' => 'COD',
                    'langEN' => 'The Democratic Republic Of The Congo',
                    'langIT' => 'Repubblica Democratica del Congo',
                    'nazionalitaEN' => 'Congolese',
                    'nazionalitaIT' => 'Congolese',
                    'bandiera' => 'democratic-republic-of-congo.svg',
                ),
            39 =>
                array(
                    'alpha2' => 'CF',
                    'alpha3' => 'CAF',
                    'langEN' => 'Central African Republic',
                    'langIT' => 'Repubblica Centroafricana',
                    'nazionalitaEN' => 'Central African',
                    'nazionalitaIT' => 'Centroafricano',
                    'bandiera' => 'central-african-republic.svg',
                ),
            40 =>
                array(
                    'alpha2' => 'CG',
                    'alpha3' => 'COG',
                    'langEN' => 'Republic of the Congo',
                    'langIT' => 'Repubblica del Congo',
                    'nazionalitaEN' => 'Congolese',
                    'nazionalitaIT' => 'Congolese',
                    'bandiera' => 'republic-of-the-congo.svg',
                ),
            41 =>
                array(
                    'alpha2' => 'CH',
                    'alpha3' => 'CHE',
                    'langEN' => 'Switzerland',
                    'langIT' => 'Svizzera',
                    'nazionalitaEN' => 'Swiss',
                    'nazionalitaIT' => 'Svizzero',
                    'bandiera' => 'switzerland.svg',
                ),
            42 =>
                array(
                    'alpha2' => 'CI',
                    'alpha3' => 'CIV',
                    'langEN' => 'Côte d\'Ivoire',
                    'langIT' => 'Costa d\'Avorio',
                    'nazionalitaEN' => 'Ivorian',
                    'nazionalitaIT' => 'Ivoriano',
                    'bandiera' => 'ivory-coast.svg',
                ),
            43 =>
                array(
                    'alpha2' => 'CK',
                    'alpha3' => 'COK',
                    'langEN' => 'Cook Islands',
                    'langIT' => 'Isole Cook',
                    'nazionalitaEN' => 'Cook Island',
                    'nazionalitaIT' => 'Isola di Cook',
                    'bandiera' => 'cook-islands.svg',
                ),
            44 =>
                array(
                    'alpha2' => 'CL',
                    'alpha3' => 'CHL',
                    'langEN' => 'Chile',
                    'langIT' => 'Cile',
                    'nazionalitaEN' => 'Chilean',
                    'nazionalitaIT' => 'Cileno',
                    'bandiera' => 'chile.svg',
                ),
            45 =>
                array(
                    'alpha2' => 'CM',
                    'alpha3' => 'CMR',
                    'langEN' => 'Cameroon',
                    'langIT' => 'Camerun',
                    'nazionalitaEN' => 'Cameroonian',
                    'nazionalitaIT' => 'Camerun',
                    'bandiera' => 'cameroon.svg',
                ),
            46 =>
                array(
                    'alpha2' => 'CN',
                    'alpha3' => 'CHN',
                    'langEN' => 'China',
                    'langIT' => 'Cina',
                    'nazionalitaEN' => 'Chinese',
                    'nazionalitaIT' => 'Cinese',
                    'bandiera' => 'china.svg',
                ),
            47 =>
                array(
                    'alpha2' => 'CO',
                    'alpha3' => 'COL',
                    'langEN' => 'Colombia',
                    'langIT' => 'Colombia',
                    'nazionalitaEN' => 'Colombian',
                    'nazionalitaIT' => 'Colombiano',
                    'bandiera' => 'colombia.svg',
                ),
            48 =>
                array(
                    'alpha2' => 'CR',
                    'alpha3' => 'CRI',
                    'langEN' => 'Costa Rica',
                    'langIT' => 'Costa Rica',
                    'nazionalitaEN' => 'Costa Rican',
                    'nazionalitaIT' => 'Costaricano',
                    'bandiera' => 'costa-rica.svg',
                ),
            49 =>
                array(
                    'alpha2' => 'CS',
                    'alpha3' => 'SCG',
                    'langEN' => 'Serbia and Montenegro',
                    'langIT' => 'Serbia e Montenegro',
                    'nazionalitaEN' => NULL,
                    'nazionalitaIT' => NULL,
                    'bandiera' => 'serbia.svg',
                ),
            50 =>
                array(
                    'alpha2' => 'CU',
                    'alpha3' => 'CUB',
                    'langEN' => 'Cuba',
                    'langIT' => 'Cuba',
                    'nazionalitaEN' => 'Cuban',
                    'nazionalitaIT' => 'Cubano',
                    'bandiera' => 'cuba.svg',
                ),
            51 =>
                array(
                    'alpha2' => 'CV',
                    'alpha3' => 'CPV',
                    'langEN' => 'Cape Verde',
                    'langIT' => 'Capo Verde',
                    'nazionalitaEN' => 'Cabo Verdean',
                    'nazionalitaIT' => 'Capo Verde',
                    'bandiera' => 'cape-verde.svg',
                ),
            52 =>
                array(
                    'alpha2' => 'CX',
                    'alpha3' => 'CXR',
                    'langEN' => 'Christmas Island',
                    'langIT' => 'Isola di Natale',
                    'nazionalitaEN' => 'Christmas Island',
                    'nazionalitaIT' => 'Isola di Natale',
                    'bandiera' => 'christmas-island.svg',
                ),
            53 =>
                array(
                    'alpha2' => 'CY',
                    'alpha3' => 'CYP',
                    'langEN' => 'Cyprus',
                    'langIT' => 'Cipro',
                    'nazionalitaEN' => 'Cypriot',
                    'nazionalitaIT' => 'Cipriota',
                    'bandiera' => 'cyprus.svg',
                ),
            54 =>
                array(
                    'alpha2' => 'CZ',
                    'alpha3' => 'CZE',
                    'langEN' => 'Czech Republic',
                    'langIT' => 'Repubblica Ceca',
                    'nazionalitaEN' => 'Czech',
                    'nazionalitaIT' => 'Ceco',
                    'bandiera' => 'czech-republic.svg',
                ),
            55 =>
                array(
                    'alpha2' => 'DE',
                    'alpha3' => 'DEU',
                    'langEN' => 'Germany',
                    'langIT' => 'Germania',
                    'nazionalitaEN' => 'German',
                    'nazionalitaIT' => 'Tedesco',
                    'bandiera' => 'germany.svg',
                ),
            56 =>
                array(
                    'alpha2' => 'DJ',
                    'alpha3' => 'DJI',
                    'langEN' => 'Djibouti',
                    'langIT' => 'Gibuti',
                    'nazionalitaEN' => 'Djiboutian',
                    'nazionalitaIT' => 'Gibuti',
                    'bandiera' => 'djibouti.svg',
                ),
            57 =>
                array(
                    'alpha2' => 'DK',
                    'alpha3' => 'DNK',
                    'langEN' => 'Denmark',
                    'langIT' => 'Danimarca',
                    'nazionalitaEN' => 'Danish',
                    'nazionalitaIT' => 'Danese',
                    'bandiera' => 'denmark.svg',
                ),
            58 =>
                array(
                    'alpha2' => 'DM',
                    'alpha3' => 'DMA',
                    'langEN' => 'Dominica',
                    'langIT' => 'Dominica',
                    'nazionalitaEN' => 'Dominican',
                    'nazionalitaIT' => 'Domenicano',
                    'bandiera' => 'dominica.svg',
                ),
            59 =>
                array(
                    'alpha2' => 'DO',
                    'alpha3' => 'DOM',
                    'langEN' => 'Dominican Republic',
                    'langIT' => 'Repubblica Dominicana',
                    'nazionalitaEN' => 'Dominican',
                    'nazionalitaIT' => 'Domenicano',
                    'bandiera' => 'dominican-republic.svg',
                ),
            60 =>
                array(
                    'alpha2' => 'DZ',
                    'alpha3' => 'DZA',
                    'langEN' => 'Algeria',
                    'langIT' => 'Algeria',
                    'nazionalitaEN' => 'Algerian',
                    'nazionalitaIT' => 'Algerino',
                    'bandiera' => 'algeria.svg',
                ),
            61 =>
                array(
                    'alpha2' => 'EC',
                    'alpha3' => 'ECU',
                    'langEN' => 'Ecuador',
                    'langIT' => 'Ecuador',
                    'nazionalitaEN' => 'Ecuadorian',
                    'nazionalitaIT' => 'Ecuadoriano',
                    'bandiera' => 'ecuador.svg',
                ),
            62 =>
                array(
                    'alpha2' => 'EE',
                    'alpha3' => 'EST',
                    'langEN' => 'Estonia',
                    'langIT' => 'Estonia',
                    'nazionalitaEN' => 'Estonian',
                    'nazionalitaIT' => 'Estone',
                    'bandiera' => 'estonia.svg',
                ),
            63 =>
                array(
                    'alpha2' => 'EG',
                    'alpha3' => 'EGY',
                    'langEN' => 'Egypt',
                    'langIT' => 'Egitto',
                    'nazionalitaEN' => 'Egyptian',
                    'nazionalitaIT' => 'Egiziano',
                    'bandiera' => 'egypt.svg',
                ),
            64 =>
                array(
                    'alpha2' => 'EH',
                    'alpha3' => 'ESH',
                    'langEN' => 'Western Sahara',
                    'langIT' => 'Sahara Occidentale',
                    'nazionalitaEN' => 'Sahrawi, Sahrawian, Sahraouian',
                    'nazionalitaIT' => 'Deserto, deserto, deserto',
                    'bandiera' => 'sahrawi-arab-democratic-republic.svg',
                ),
            65 =>
                array(
                    'alpha2' => 'ER',
                    'alpha3' => 'ERI',
                    'langEN' => 'Eritrea',
                    'langIT' => 'Eritrea',
                    'nazionalitaEN' => 'Eritrean',
                    'nazionalitaIT' => 'Eritreo',
                    'bandiera' => 'eritrea.svg',
                ),
            66 =>
                array(
                    'alpha2' => 'ES',
                    'alpha3' => 'ESP',
                    'langEN' => 'Spain',
                    'langIT' => 'Spagna',
                    'nazionalitaEN' => 'Spanish',
                    'nazionalitaIT' => 'Spagnolo',
                    'bandiera' => 'spain.svg',
                ),
            67 =>
                array(
                    'alpha2' => 'ET',
                    'alpha3' => 'ETH',
                    'langEN' => 'Ethiopia',
                    'langIT' => 'Etiopia',
                    'nazionalitaEN' => 'Ethiopian',
                    'nazionalitaIT' => 'Etiope',
                    'bandiera' => 'ethiopia.svg',
                ),
            68 =>
                array(
                    'alpha2' => 'FI',
                    'alpha3' => 'FIN',
                    'langEN' => 'Finland',
                    'langIT' => 'Finlandia',
                    'nazionalitaEN' => 'Finnish',
                    'nazionalitaIT' => 'Finlandese',
                    'bandiera' => 'finland.svg',
                ),
            69 =>
                array(
                    'alpha2' => 'FJ',
                    'alpha3' => 'FJI',
                    'langEN' => 'Fiji',
                    'langIT' => 'Fiji',
                    'nazionalitaEN' => 'Fijian',
                    'nazionalitaIT' => 'Figiano',
                    'bandiera' => 'fiji.svg',
                ),
            70 =>
                array(
                    'alpha2' => 'FK',
                    'alpha3' => 'FLK',
                    'langEN' => 'Falkland Islands',
                    'langIT' => 'Isole Falkland',
                    'nazionalitaEN' => 'Falkland Island',
                    'nazionalitaIT' => 'Isole Falkland',
                    'bandiera' => 'falkland-islands.svg',
                ),
            71 =>
                array(
                    'alpha2' => 'FM',
                    'alpha3' => 'FSM',
                    'langEN' => 'Federated States of Micronesia',
                    'langIT' => 'Stati Federati della Micronesia',
                    'nazionalitaEN' => 'Micronesian',
                    'nazionalitaIT' => 'Micronesiano',
                    'bandiera' => 'micronesia.svg',
                ),
            72 =>
                array(
                    'alpha2' => 'FO',
                    'alpha3' => 'FRO',
                    'langEN' => 'Faroe Islands',
                    'langIT' => 'Isole Faroe',
                    'nazionalitaEN' => 'Faroese',
                    'nazionalitaIT' => 'Faroese',
                    'bandiera' => 'faroe-islands.svg',
                ),
            73 =>
                array(
                    'alpha2' => 'FR',
                    'alpha3' => 'FRA',
                    'langEN' => 'France',
                    'langIT' => 'Francia',
                    'nazionalitaEN' => 'French',
                    'nazionalitaIT' => 'Francese',
                    'bandiera' => 'france.svg',
                ),
            74 =>
                array(
                    'alpha2' => 'GA',
                    'alpha3' => 'GAB',
                    'langEN' => 'Gabon',
                    'langIT' => 'Gabon',
                    'nazionalitaEN' => 'Gabonese',
                    'nazionalitaIT' => 'Gabon',
                    'bandiera' => 'gabon.svg',
                ),
            75 =>
                array(
                    'alpha2' => 'GB',
                    'alpha3' => 'GBR',
                    'langEN' => 'United Kingdom',
                    'langIT' => 'Regno Unito',
                    'nazionalitaEN' => 'British, UK',
                    'nazionalitaIT' => 'Britannico, Regno Unito',
                    'bandiera' => 'united-kingdom.svg',
                ),
            76 =>
                array(
                    'alpha2' => 'GD',
                    'alpha3' => 'GRD',
                    'langEN' => 'Grenada',
                    'langIT' => 'Grenada',
                    'nazionalitaEN' => 'Grenadian',
                    'nazionalitaIT' => 'Grenadian',
                    'bandiera' => 'grenada.svg',
                ),
            77 =>
                array(
                    'alpha2' => 'GE',
                    'alpha3' => 'GEO',
                    'langEN' => 'Georgia',
                    'langIT' => 'Georgia',
                    'nazionalitaEN' => 'Georgian',
                    'nazionalitaIT' => 'Georgiano',
                    'bandiera' => 'georgia.svg',
                ),
            78 =>
                array(
                    'alpha2' => 'GF',
                    'alpha3' => 'GUF',
                    'langEN' => 'French Guiana',
                    'langIT' => 'Guyana Francese',
                    'nazionalitaEN' => 'French Guianese',
                    'nazionalitaIT' => 'Guianese francese',
                    'bandiera' => 'france.svg',
                ),
            79 =>
                array(
                    'alpha2' => 'GH',
                    'alpha3' => 'GHA',
                    'langEN' => 'Ghana',
                    'langIT' => 'Ghana',
                    'nazionalitaEN' => 'Ghanaian',
                    'nazionalitaIT' => 'Ghanese',
                    'bandiera' => 'ghana.svg',
                ),
            80 =>
                array(
                    'alpha2' => 'GI',
                    'alpha3' => 'GIB',
                    'langEN' => 'Gibraltar',
                    'langIT' => 'Gibilterra',
                    'nazionalitaEN' => 'Gibraltar',
                    'nazionalitaIT' => 'Gibilterra',
                    'bandiera' => 'gibraltar.svg',
                ),
            81 =>
                array(
                    'alpha2' => 'GL',
                    'alpha3' => 'GRL',
                    'langEN' => 'Greenland',
                    'langIT' => 'Groenlandia',
                    'nazionalitaEN' => 'Greenlandic',
                    'nazionalitaIT' => 'Groenlandese',
                    'bandiera' => 'greenland.svg',
                ),
            82 =>
                array(
                    'alpha2' => 'GM',
                    'alpha3' => 'GMB',
                    'langEN' => 'Gambia',
                    'langIT' => 'Gambia',
                    'nazionalitaEN' => 'Gambian',
                    'nazionalitaIT' => 'Gambiano',
                    'bandiera' => 'gambia.svg',
                ),
            83 =>
                array(
                    'alpha2' => 'GN',
                    'alpha3' => 'GIN',
                    'langEN' => 'Guinea',
                    'langIT' => 'Guinea',
                    'nazionalitaEN' => 'Guinean',
                    'nazionalitaIT' => 'Guineano',
                    'bandiera' => 'guinea.svg',
                ),
            84 =>
                array(
                    'alpha2' => 'GP',
                    'alpha3' => 'GLP',
                    'langEN' => 'Guadeloupe',
                    'langIT' => 'Guadalupa',
                    'nazionalitaEN' => 'Guadeloupe',
                    'nazionalitaIT' => 'Guadalupa',
                    'bandiera' => 'france.svg',
                ),
            85 =>
                array(
                    'alpha2' => 'GQ',
                    'alpha3' => 'GNQ',
                    'langEN' => 'Equatorial Guinea',
                    'langIT' => 'Guinea Equatoriale',
                    'nazionalitaEN' => 'Equatorial Guinean, Equatoguinean',
                    'nazionalitaIT' => 'Guinea Equatoriale, Equatoguineana',
                    'bandiera' => 'equatorial-guinea.svg',
                ),
            86 =>
                array(
                    'alpha2' => 'GR',
                    'alpha3' => 'GRC',
                    'langEN' => 'Greece',
                    'langIT' => 'Grecia',
                    'nazionalitaEN' => 'Greek, Hellenic',
                    'nazionalitaIT' => 'Greco, ellenico',
                    'bandiera' => 'greece.svg',
                ),
            87 =>
                array(
                    'alpha2' => 'GS',
                    'alpha3' => 'SGS',
                    'langEN' => 'South Georgia and the South Sandwich Islands',
                    'langIT' => 'Sud Georgia e Isole Sandwich',
                    'nazionalitaEN' => 'South Georgia or South Sandwich Islands',
                    'nazionalitaIT' => 'Georgia del Sud o Isole Sandwich Meridionali',
                    'bandiera' => 'flag.svg',
                ),
            88 =>
                array(
                    'alpha2' => 'GT',
                    'alpha3' => 'GTM',
                    'langEN' => 'Guatemala',
                    'langIT' => 'Guatemala',
                    'nazionalitaEN' => 'Guatemalan',
                    'nazionalitaIT' => 'Guatemalteco',
                    'bandiera' => 'guatemala.svg',
                ),
            89 =>
                array(
                    'alpha2' => 'GU',
                    'alpha3' => 'GUM',
                    'langEN' => 'Guam',
                    'langIT' => 'Guam',
                    'nazionalitaEN' => 'Guamanian, Guambat',
                    'nazionalitaIT' => 'Guamanian, Guambat',
                    'bandiera' => 'guam.svg',
                ),
            90 =>
                array(
                    'alpha2' => 'GW',
                    'alpha3' => 'GNB',
                    'langEN' => 'Guinea-Bissau',
                    'langIT' => 'Guinea-Bissau',
                    'nazionalitaEN' => 'Bissau-Guinean',
                    'nazionalitaIT' => 'Guinea-Bissau',
                    'bandiera' => 'guinea-bissau.svg',
                ),
            91 =>
                array(
                    'alpha2' => 'GY',
                    'alpha3' => 'GUY',
                    'langEN' => 'Guyana',
                    'langIT' => 'Guyana',
                    'nazionalitaEN' => 'Guyanese',
                    'nazionalitaIT' => 'Guyana',
                    'bandiera' => 'guyana.svg',
                ),
            92 =>
                array(
                    'alpha2' => 'HK',
                    'alpha3' => 'HKG',
                    'langEN' => 'Hong Kong',
                    'langIT' => 'Hong Kong',
                    'nazionalitaEN' => 'Hong Kong, Hong Kongese',
                    'nazionalitaIT' => 'Hong Kong, Hong Kong',
                    'bandiera' => 'hong-kong.svg',
                ),
            93 =>
                array(
                    'alpha2' => 'HM',
                    'alpha3' => 'HMD',
                    'langEN' => 'Heard Island and McDonald Islands',
                    'langIT' => 'Isola Heard e Isole McDonald',
                    'nazionalitaEN' => 'Heard Island or McDonald Islands',
                    'nazionalitaIT' => 'Isole Heard e McDonald',
                    'bandiera' => 'flag.svg',
                ),
            94 =>
                array(
                    'alpha2' => 'HN',
                    'alpha3' => 'HND',
                    'langEN' => 'Honduras',
                    'langIT' => 'Honduras',
                    'nazionalitaEN' => 'Honduran',
                    'nazionalitaIT' => 'Honduregno',
                    'bandiera' => 'honduras.svg',
                ),
            95 =>
                array(
                    'alpha2' => 'HR',
                    'alpha3' => 'HRV',
                    'langEN' => 'Croatia',
                    'langIT' => 'Croazia',
                    'nazionalitaEN' => 'Croatian',
                    'nazionalitaIT' => 'Croato',
                    'bandiera' => 'croatia.svg',
                ),
            96 =>
                array(
                    'alpha2' => 'HT',
                    'alpha3' => 'HTI',
                    'langEN' => 'Haiti',
                    'langIT' => 'Haiti',
                    'nazionalitaEN' => 'Haitian',
                    'nazionalitaIT' => 'Haitiano',
                    'bandiera' => 'haiti.svg',
                ),
            97 =>
                array(
                    'alpha2' => 'HU',
                    'alpha3' => 'HUN',
                    'langEN' => 'Hungary',
                    'langIT' => 'Ungheria',
                    'nazionalitaEN' => 'Hungarian, Magyar',
                    'nazionalitaIT' => 'Ungherese, magiaro',
                    'bandiera' => 'hungary.svg',
                ),
            98 =>
                array(
                    'alpha2' => 'ID',
                    'alpha3' => 'IDN',
                    'langEN' => 'Indonesia',
                    'langIT' => 'Indonesia',
                    'nazionalitaEN' => 'Indonesian',
                    'nazionalitaIT' => 'Indonesiano',
                    'bandiera' => 'indonesia.svg',
                ),
            99 =>
                array(
                    'alpha2' => 'IE',
                    'alpha3' => 'IRL',
                    'langEN' => 'Ireland',
                    'langIT' => 'Irlanda',
                    'nazionalitaEN' => 'Irish',
                    'nazionalitaIT' => 'Irlandesi',
                    'bandiera' => 'ireland.svg',
                ),
            100 =>
                array(
                    'alpha2' => 'IL',
                    'alpha3' => 'ISR',
                    'langEN' => 'Israel',
                    'langIT' => 'Israele',
                    'nazionalitaEN' => 'Israeli',
                    'nazionalitaIT' => 'Israeliano',
                    'bandiera' => 'israel.svg',
                ),
            101 =>
                array(
                    'alpha2' => 'IM',
                    'alpha3' => 'IMN',
                    'langEN' => 'Isle of Man',
                    'langIT' => 'Isola di Man',
                    'nazionalitaEN' => 'Manx',
                    'nazionalitaIT' => 'Manx',
                    'bandiera' => 'isle-of-man.svg',
                ),
            102 =>
                array(
                    'alpha2' => 'IN',
                    'alpha3' => 'IND',
                    'langEN' => 'India',
                    'langIT' => 'India',
                    'nazionalitaEN' => 'Indian',
                    'nazionalitaIT' => 'Indiano',
                    'bandiera' => 'india.svg',
                ),
            103 =>
                array(
                    'alpha2' => 'IO',
                    'alpha3' => 'IOT',
                    'langEN' => 'British Indian Ocean Territory',
                    'langIT' => 'Territori Britannici dell\'Oceano Indiano',
                    'nazionalitaEN' => 'BIOT',
                    'nazionalitaIT' => 'BIOT',
                    'bandiera' => 'british-indian-ocean-territory.svg',
                ),
            104 =>
                array(
                    'alpha2' => 'IQ',
                    'alpha3' => 'IRQ',
                    'langEN' => 'Iraq',
                    'langIT' => 'Iraq',
                    'nazionalitaEN' => 'Iraqi',
                    'nazionalitaIT' => 'Iracheno',
                    'bandiera' => 'iraq.svg',
                ),
            105 =>
                array(
                    'alpha2' => 'IR',
                    'alpha3' => 'IRN',
                    'langEN' => 'Islamic Republic of Iran',
                    'langIT' => 'Iran',
                    'nazionalitaEN' => 'Iranian, Persian',
                    'nazionalitaIT' => 'Iraniano, persiano',
                    'bandiera' => 'iran.svg',
                ),
            106 =>
                array(
                    'alpha2' => 'IS',
                    'alpha3' => 'ISL',
                    'langEN' => 'Iceland',
                    'langIT' => 'Islanda',
                    'nazionalitaEN' => 'Icelandic',
                    'nazionalitaIT' => 'Islandese',
                    'bandiera' => 'iceland.svg',
                ),
            107 =>
                array(
                    'alpha2' => 'IT',
                    'alpha3' => 'ITA',
                    'langEN' => 'Italy',
                    'langIT' => 'Italia',
                    'nazionalitaEN' => 'Italian',
                    'nazionalitaIT' => 'Italiano',
                    'bandiera' => 'italy.svg',
                ),
            108 =>
                array(
                    'alpha2' => 'JM',
                    'alpha3' => 'JAM',
                    'langEN' => 'Jamaica',
                    'langIT' => 'Giamaica',
                    'nazionalitaEN' => 'Jamaican',
                    'nazionalitaIT' => 'Giamaicano',
                    'bandiera' => 'jamaica.svg',
                ),
            109 =>
                array(
                    'alpha2' => 'JO',
                    'alpha3' => 'JOR',
                    'langEN' => 'Jordan',
                    'langIT' => 'Giordania',
                    'nazionalitaEN' => 'Jordanian',
                    'nazionalitaIT' => 'Giordano',
                    'bandiera' => 'jordan.svg',
                ),
            110 =>
                array(
                    'alpha2' => 'JP',
                    'alpha3' => 'JPN',
                    'langEN' => 'Japan',
                    'langIT' => 'Giappone',
                    'nazionalitaEN' => 'Japanese',
                    'nazionalitaIT' => 'Giapponese',
                    'bandiera' => 'japan.svg',
                ),
            111 =>
                array(
                    'alpha2' => 'KE',
                    'alpha3' => 'KEN',
                    'langEN' => 'Kenya',
                    'langIT' => 'Kenya',
                    'nazionalitaEN' => 'Kenyan',
                    'nazionalitaIT' => 'Keniota',
                    'bandiera' => 'kenya.svg',
                ),
            112 =>
                array(
                    'alpha2' => 'KG',
                    'alpha3' => 'KGZ',
                    'langEN' => 'Kyrgyzstan',
                    'langIT' => 'Kirghizistan',
                    'nazionalitaEN' => 'Kyrgyzstani, Kyrgyz, Kirgiz, Kirghiz',
                    'nazionalitaIT' => 'Kirghizistan, Kirghizistan, Kirghizistan, Kirghiz',
                    'bandiera' => 'kyrgyzstan.svg',
                ),
            113 =>
                array(
                    'alpha2' => 'KH',
                    'alpha3' => 'KHM',
                    'langEN' => 'Cambodia',
                    'langIT' => 'Cambogia',
                    'nazionalitaEN' => 'Cambodian',
                    'nazionalitaIT' => 'Cambogiano',
                    'bandiera' => 'cambodia.svg',
                ),
            114 =>
                array(
                    'alpha2' => 'KI',
                    'alpha3' => 'KIR',
                    'langEN' => 'Kiribati',
                    'langIT' => 'Kiribati',
                    'nazionalitaEN' => 'I-Kiribati',
                    'nazionalitaIT' => 'Kiribati',
                    'bandiera' => 'kiribati.svg',
                ),
            115 =>
                array(
                    'alpha2' => 'KM',
                    'alpha3' => 'COM',
                    'langEN' => 'Comoros',
                    'langIT' => 'Comore',
                    'nazionalitaEN' => 'Comoran, Comorian',
                    'nazionalitaIT' => 'Comore, Comore',
                    'bandiera' => 'comoros.svg',
                ),
            116 =>
                array(
                    'alpha2' => 'KN',
                    'alpha3' => 'KNA',
                    'langEN' => 'Saint Kitts and Nevis',
                    'langIT' => 'Saint Kitts e Nevis',
                    'nazionalitaEN' => 'Kittitian or Nevisian',
                    'nazionalitaIT' => 'Kittitian o Nevisian',
                    'bandiera' => 'saint-kitts-and-nevis.svg',
                ),
            117 =>
                array(
                    'alpha2' => 'KP',
                    'alpha3' => 'PRK',
                    'langEN' => 'Democratic People\'s Republic of Korea',
                    'langIT' => 'Corea del Nord',
                    'nazionalitaEN' => 'North Korean',
                    'nazionalitaIT' => 'Corea del nord',
                    'bandiera' => 'north-korea.svg',
                ),
            118 =>
                array(
                    'alpha2' => 'KR',
                    'alpha3' => 'KOR',
                    'langEN' => 'Republic of Korea',
                    'langIT' => 'Corea del Sud',
                    'nazionalitaEN' => 'South Korean',
                    'nazionalitaIT' => 'Corea del Sud',
                    'bandiera' => 'south-korea.svg',
                ),
            119 =>
                array(
                    'alpha2' => 'KW',
                    'alpha3' => 'KWT',
                    'langEN' => 'Kuwait',
                    'langIT' => 'Kuwait',
                    'nazionalitaEN' => 'Kuwaiti',
                    'nazionalitaIT' => 'Kuwaitiano',
                    'bandiera' => 'kuwait.svg',
                ),
            120 =>
                array(
                    'alpha2' => 'KY',
                    'alpha3' => 'CYM',
                    'langEN' => 'Cayman Islands',
                    'langIT' => 'Isole Cayman',
                    'nazionalitaEN' => 'Caymanian',
                    'nazionalitaIT' => 'Caimano',
                    'bandiera' => 'cayman-islands.svg',
                ),
            121 =>
                array(
                    'alpha2' => 'KZ',
                    'alpha3' => 'KAZ',
                    'langEN' => 'Kazakhstan',
                    'langIT' => 'Kazakhistan',
                    'nazionalitaEN' => 'Kazakhstani, Kazakh',
                    'nazionalitaIT' => 'Kazako, kazako',
                    'bandiera' => 'kazakhstan.svg',
                ),
            122 =>
                array(
                    'alpha2' => 'LA',
                    'alpha3' => 'LAO',
                    'langEN' => 'Lao People\'s Democratic Republic',
                    'langIT' => 'Laos',
                    'nazionalitaEN' => 'Lao, Laotian',
                    'nazionalitaIT' => 'Lao, laotiano',
                    'bandiera' => 'laos.svg',
                ),
            123 =>
                array(
                    'alpha2' => 'LB',
                    'alpha3' => 'LBN',
                    'langEN' => 'Lebanon',
                    'langIT' => 'Libano',
                    'nazionalitaEN' => 'Lebanese',
                    'nazionalitaIT' => 'Libanese',
                    'bandiera' => 'lebanon.svg',
                ),
            124 =>
                array(
                    'alpha2' => 'LC',
                    'alpha3' => 'LCA',
                    'langEN' => 'Saint Lucia',
                    'langIT' => 'Santa Lucia',
                    'nazionalitaEN' => 'Saint Lucian',
                    'nazionalitaIT' => 'San Luciano',
                    'bandiera' => 'st-lucia.svg',
                ),
            125 =>
                array(
                    'alpha2' => 'LI',
                    'alpha3' => 'LIE',
                    'langEN' => 'Liechtenstein',
                    'langIT' => 'Liechtenstein',
                    'nazionalitaEN' => 'Liechtenstein',
                    'nazionalitaIT' => 'Liechtenstein',
                    'bandiera' => 'liechtenstein.svg',
                ),
            126 =>
                array(
                    'alpha2' => 'LK',
                    'alpha3' => 'LKA',
                    'langEN' => 'Sri Lanka',
                    'langIT' => 'Sri Lanka',
                    'nazionalitaEN' => 'Sri Lankan',
                    'nazionalitaIT' => 'Sri Lanka',
                    'bandiera' => 'sri-lanka.svg',
                ),
            127 =>
                array(
                    'alpha2' => 'LR',
                    'alpha3' => 'LBR',
                    'langEN' => 'Liberia',
                    'langIT' => 'Liberia',
                    'nazionalitaEN' => 'Liberian',
                    'nazionalitaIT' => 'Liberiano',
                    'bandiera' => 'liberia.svg',
                ),
            128 =>
                array(
                    'alpha2' => 'LS',
                    'alpha3' => 'LSO',
                    'langEN' => 'Lesotho',
                    'langIT' => 'Lesotho',
                    'nazionalitaEN' => 'Basotho',
                    'nazionalitaIT' => 'Basotho',
                    'bandiera' => 'lesotho.svg',
                ),
            129 =>
                array(
                    'alpha2' => 'LT',
                    'alpha3' => 'LTU',
                    'langEN' => 'Lithuania',
                    'langIT' => 'Lituania',
                    'nazionalitaEN' => 'Lithuanian',
                    'nazionalitaIT' => 'Lituano',
                    'bandiera' => 'lithuania.svg',
                ),
            130 =>
                array(
                    'alpha2' => 'LU',
                    'alpha3' => 'LUX',
                    'langEN' => 'Luxembourg',
                    'langIT' => 'Lussemburgo',
                    'nazionalitaEN' => 'Luxembourg, Luxembourgish',
                    'nazionalitaIT' => 'Lussemburghese, lussemburghese',
                    'bandiera' => 'luxembourg.svg',
                ),
            131 =>
                array(
                    'alpha2' => 'LV',
                    'alpha3' => 'LVA',
                    'langEN' => 'Latvia',
                    'langIT' => 'Lettonia',
                    'nazionalitaEN' => 'Latvian',
                    'nazionalitaIT' => 'Lettone',
                    'bandiera' => 'latvia.svg',
                ),
            132 =>
                array(
                    'alpha2' => 'LY',
                    'alpha3' => 'LBY',
                    'langEN' => 'Libyan Arab Jamahiriya',
                    'langIT' => 'Libia',
                    'nazionalitaEN' => 'Libyan',
                    'nazionalitaIT' => 'Libico',
                    'bandiera' => 'libya.svg',
                ),
            133 =>
                array(
                    'alpha2' => 'MA',
                    'alpha3' => 'MAR',
                    'langEN' => 'Morocco',
                    'langIT' => 'Marocco',
                    'nazionalitaEN' => 'Moroccan',
                    'nazionalitaIT' => 'Marocchino',
                    'bandiera' => 'morocco.svg',
                ),
            134 =>
                array(
                    'alpha2' => 'MC',
                    'alpha3' => 'MCO',
                    'langEN' => 'Monaco',
                    'langIT' => 'Monaco',
                    'nazionalitaEN' => 'Monégasque, Monacan',
                    'nazionalitaIT' => 'Monegasco, monaco',
                    'bandiera' => 'monaco.svg',
                ),
            135 =>
                array(
                    'alpha2' => 'MD',
                    'alpha3' => 'MDA',
                    'langEN' => 'Republic of Moldova',
                    'langIT' => 'Moldavia',
                    'nazionalitaEN' => 'Moldovan',
                    'nazionalitaIT' => 'Moldavo',
                    'bandiera' => 'moldova.svg',
                ),
            136 =>
                array(
                    'alpha2' => 'MG',
                    'alpha3' => 'MDG',
                    'langEN' => 'Madagascar',
                    'langIT' => 'Madagascar',
                    'nazionalitaEN' => 'Malagasy',
                    'nazionalitaIT' => 'Malgascio',
                    'bandiera' => 'madagascar.svg',
                ),
            137 =>
                array(
                    'alpha2' => 'MH',
                    'alpha3' => 'MHL',
                    'langEN' => 'Marshall Islands',
                    'langIT' => 'Isole Marshall',
                    'nazionalitaEN' => 'Marshallese',
                    'nazionalitaIT' => 'Marshallese',
                    'bandiera' => 'marshall-island.svg',
                ),
            138 =>
                array(
                    'alpha2' => 'MK',
                    'alpha3' => 'MKD',
                    'langEN' => 'The Former Yugoslav Republic of Macedonia',
                    'langIT' => 'Macedonia',
                    'nazionalitaEN' => 'Macedonian',
                    'nazionalitaIT' => 'Macedone',
                    'bandiera' => 'republic-of-macedonia.svg',
                ),
            139 =>
                array(
                    'alpha2' => 'ML',
                    'alpha3' => 'MLI',
                    'langEN' => 'Mali',
                    'langIT' => 'Mali',
                    'nazionalitaEN' => 'Malian, Malinese',
                    'nazionalitaIT' => 'Maliano, maliano',
                    'bandiera' => 'mali.svg',
                ),
            140 =>
                array(
                    'alpha2' => 'MM',
                    'alpha3' => 'MMR',
                    'langEN' => 'Myanmar',
                    'langIT' => 'Myanmar',
                    'nazionalitaEN' => 'Burmese',
                    'nazionalitaIT' => 'Birmano',
                    'bandiera' => 'myanmar.svg',
                ),
            141 =>
                array(
                    'alpha2' => 'MN',
                    'alpha3' => 'MNG',
                    'langEN' => 'Mongolia',
                    'langIT' => 'Mongolia',
                    'nazionalitaEN' => 'Mongolian',
                    'nazionalitaIT' => 'Mongolo',
                    'bandiera' => 'mongolia.svg',
                ),
            142 =>
                array(
                    'alpha2' => 'MO',
                    'alpha3' => 'MAC',
                    'langEN' => 'Macao',
                    'langIT' => 'Macao',
                    'nazionalitaEN' => 'Macanese, Chinese',
                    'nazionalitaIT' => 'Macanese, Cinese',
                    'bandiera' => 'macao.svg',
                ),
            143 =>
                array(
                    'alpha2' => 'MP',
                    'alpha3' => 'MNP',
                    'langEN' => 'Northern Mariana Islands',
                    'langIT' => 'Isole Marianne Settentrionali',
                    'nazionalitaEN' => 'Northern Marianan',
                    'nazionalitaIT' => 'Marianne Settentrionali',
                    'bandiera' => 'northern-mariana-islands.svg',
                ),
            144 =>
                array(
                    'alpha2' => 'MQ',
                    'alpha3' => 'MTQ',
                    'langEN' => 'Martinique',
                    'langIT' => 'Martinica',
                    'nazionalitaEN' => 'Martiniquais, Martinican',
                    'nazionalitaIT' => 'Martiniquais, Martinica',
                    'bandiera' => 'france.svg',
                ),
            145 =>
                array(
                    'alpha2' => 'MR',
                    'alpha3' => 'MRT',
                    'langEN' => 'Mauritania',
                    'langIT' => 'Mauritania',
                    'nazionalitaEN' => 'Mauritanian',
                    'nazionalitaIT' => 'Mauritano',
                    'bandiera' => 'mauritania.svg',
                ),
            146 =>
                array(
                    'alpha2' => 'MS',
                    'alpha3' => 'MSR',
                    'langEN' => 'Montserrat',
                    'langIT' => 'Montserrat',
                    'nazionalitaEN' => 'Montserratian',
                    'nazionalitaIT' => 'Montserratiano',
                    'bandiera' => 'montserrat.svg',
                ),
            147 =>
                array(
                    'alpha2' => 'MT',
                    'alpha3' => 'MLT',
                    'langEN' => 'Malta',
                    'langIT' => 'Malta',
                    'nazionalitaEN' => 'Maltese',
                    'nazionalitaIT' => 'Maltese',
                    'bandiera' => 'malta.svg',
                ),
            148 =>
                array(
                    'alpha2' => 'MU',
                    'alpha3' => 'MUS',
                    'langEN' => 'Mauritius',
                    'langIT' => 'Maurizius',
                    'nazionalitaEN' => 'Mauritian',
                    'nazionalitaIT' => 'Mauriziano',
                    'bandiera' => 'mauritius.svg',
                ),
            149 =>
                array(
                    'alpha2' => 'MV',
                    'alpha3' => 'MDV',
                    'langEN' => 'Maldives',
                    'langIT' => 'Maldive',
                    'nazionalitaEN' => 'Maldivian',
                    'nazionalitaIT' => 'Maldiviano',
                    'bandiera' => 'maldives.svg',
                ),
            150 =>
                array(
                    'alpha2' => 'MW',
                    'alpha3' => 'MWI',
                    'langEN' => 'Malawi',
                    'langIT' => 'Malawi',
                    'nazionalitaEN' => 'Malawian',
                    'nazionalitaIT' => 'Malawi',
                    'bandiera' => 'malawi.svg',
                ),
            151 =>
                array(
                    'alpha2' => 'MX',
                    'alpha3' => 'MEX',
                    'langEN' => 'Mexico',
                    'langIT' => 'Messico',
                    'nazionalitaEN' => 'Mexican',
                    'nazionalitaIT' => 'Messicano',
                    'bandiera' => 'mexico.svg',
                ),
            152 =>
                array(
                    'alpha2' => 'MY',
                    'alpha3' => 'MYS',
                    'langEN' => 'Malaysia',
                    'langIT' => 'Malesia',
                    'nazionalitaEN' => 'Malaysian',
                    'nazionalitaIT' => 'Malese',
                    'bandiera' => 'malaysia.svg',
                ),
            153 =>
                array(
                    'alpha2' => 'MZ',
                    'alpha3' => 'MOZ',
                    'langEN' => 'Mozambique',
                    'langIT' => 'Mozambico',
                    'nazionalitaEN' => 'Mozambican',
                    'nazionalitaIT' => 'Mozambicano',
                    'bandiera' => 'mozambique.svg',
                ),
            154 =>
                array(
                    'alpha2' => 'NA',
                    'alpha3' => 'NAM',
                    'langEN' => 'Namibia',
                    'langIT' => 'Namibia',
                    'nazionalitaEN' => 'Namibian',
                    'nazionalitaIT' => 'Namibiano',
                    'bandiera' => 'namibia.svg',
                ),
            155 =>
                array(
                    'alpha2' => 'NC',
                    'alpha3' => 'NCL',
                    'langEN' => 'New Caledonia',
                    'langIT' => 'Nuova Caledonia',
                    'nazionalitaEN' => 'New Caledonian',
                    'nazionalitaIT' => 'Nuova Caledonia',
                    'bandiera' => 'france.svg',
                ),
            156 =>
                array(
                    'alpha2' => 'NE',
                    'alpha3' => 'NER',
                    'langEN' => 'Niger',
                    'langIT' => 'Niger',
                    'nazionalitaEN' => 'Nigerien',
                    'nazionalitaIT' => 'Nigeria',
                    'bandiera' => 'niger.svg',
                ),
            157 =>
                array(
                    'alpha2' => 'NF',
                    'alpha3' => 'NFK',
                    'langEN' => 'Norfolk Island',
                    'langIT' => 'Isola Norfolk',
                    'nazionalitaEN' => 'Norfolk Island',
                    'nazionalitaIT' => 'Isola Norfolk',
                    'bandiera' => 'norfolk-island.svg',
                ),
            158 =>
                array(
                    'alpha2' => 'NG',
                    'alpha3' => 'NGA',
                    'langEN' => 'Nigeria',
                    'langIT' => 'Nigeria',
                    'nazionalitaEN' => 'Nigerian',
                    'nazionalitaIT' => 'Nigeriano',
                    'bandiera' => 'nigeria.svg',
                ),
            159 =>
                array(
                    'alpha2' => 'NI',
                    'alpha3' => 'NIC',
                    'langEN' => 'Nicaragua',
                    'langIT' => 'Nicaragua',
                    'nazionalitaEN' => 'Nicaraguan',
                    'nazionalitaIT' => 'Nicaragua',
                    'bandiera' => 'nicaragua.svg',
                ),
            160 =>
                array(
                    'alpha2' => 'NL',
                    'alpha3' => 'NLD',
                    'langEN' => 'Netherlands',
                    'langIT' => 'Paesi Bassi',
                    'nazionalitaEN' => 'Dutch, Netherlandic',
                    'nazionalitaIT' => 'Olandese, olandese',
                    'bandiera' => 'netherlands.svg',
                ),
            161 =>
                array(
                    'alpha2' => 'NO',
                    'alpha3' => 'NOR',
                    'langEN' => 'Norway',
                    'langIT' => 'Norvegia',
                    'nazionalitaEN' => 'Norwegian',
                    'nazionalitaIT' => 'Norvegese',
                    'bandiera' => 'norway.svg',
                ),
            162 =>
                array(
                    'alpha2' => 'NP',
                    'alpha3' => 'NPL',
                    'langEN' => 'Nepal',
                    'langIT' => 'Nepal',
                    'nazionalitaEN' => 'Nepali, Nepalese',
                    'nazionalitaIT' => 'Nepalese, nepalese',
                    'bandiera' => 'nepal.svg',
                ),
            163 =>
                array(
                    'alpha2' => 'NR',
                    'alpha3' => 'NRU',
                    'langEN' => 'Nauru',
                    'langIT' => 'Nauru',
                    'nazionalitaEN' => 'Nauruan',
                    'nazionalitaIT' => 'Nauruan',
                    'bandiera' => 'nauru.svg',
                ),
            164 =>
                array(
                    'alpha2' => 'NU',
                    'alpha3' => 'NIU',
                    'langEN' => 'Niue',
                    'langIT' => 'Niue',
                    'nazionalitaEN' => 'Niuean',
                    'nazionalitaIT' => 'Niuean',
                    'bandiera' => 'niue.svg',
                ),
            165 =>
                array(
                    'alpha2' => 'NZ',
                    'alpha3' => 'NZL',
                    'langEN' => 'New Zealand',
                    'langIT' => 'Nuova Zelanda',
                    'nazionalitaEN' => 'New Zealand, NZ',
                    'nazionalitaIT' => 'Nuova Zelanda, Nuova Zelanda',
                    'bandiera' => 'new-zealand.svg',
                ),
            166 =>
                array(
                    'alpha2' => 'OM',
                    'alpha3' => 'OMN',
                    'langEN' => 'Oman',
                    'langIT' => 'Oman',
                    'nazionalitaEN' => 'Omani',
                    'nazionalitaIT' => 'Omani',
                    'bandiera' => 'oman.svg',
                ),
            167 =>
                array(
                    'alpha2' => 'PA',
                    'alpha3' => 'PAN',
                    'langEN' => 'Panama',
                    'langIT' => 'Panamá',
                    'nazionalitaEN' => 'Panamanian',
                    'nazionalitaIT' => 'Panamense',
                    'bandiera' => 'panama.svg',
                ),
            168 =>
                array(
                    'alpha2' => 'PE',
                    'alpha3' => 'PER',
                    'langEN' => 'Peru',
                    'langIT' => 'Perù',
                    'nazionalitaEN' => 'Peruvian',
                    'nazionalitaIT' => 'Peruviano',
                    'bandiera' => 'peru.svg',
                ),
            169 =>
                array(
                    'alpha2' => 'PF',
                    'alpha3' => 'PYF',
                    'langEN' => 'French Polynesia',
                    'langIT' => 'Polinesia Francese',
                    'nazionalitaEN' => 'French Polynesian',
                    'nazionalitaIT' => 'Polinesiano francese',
                    'bandiera' => 'french-polynesia.svg',
                ),
            170 =>
                array(
                    'alpha2' => 'PG',
                    'alpha3' => 'PNG',
                    'langEN' => 'Papua New Guinea',
                    'langIT' => 'Papua Nuova Guinea',
                    'nazionalitaEN' => 'Papua New Guinean, Papuan',
                    'nazionalitaIT' => 'Papua Nuova Guinea, Papua',
                    'bandiera' => 'papua-new-guinea.svg',
                ),
            171 =>
                array(
                    'alpha2' => 'PH',
                    'alpha3' => 'PHL',
                    'langEN' => 'Philippines',
                    'langIT' => 'Filippine',
                    'nazionalitaEN' => 'Philippine, Filipino',
                    'nazionalitaIT' => 'Filippino, filippino',
                    'bandiera' => 'philippines.svg',
                ),
            172 =>
                array(
                    'alpha2' => 'PK',
                    'alpha3' => 'PAK',
                    'langEN' => 'Pakistan',
                    'langIT' => 'Pakistan',
                    'nazionalitaEN' => 'Pakistani',
                    'nazionalitaIT' => 'Pakistano',
                    'bandiera' => 'pakistan.svg',
                ),
            173 =>
                array(
                    'alpha2' => 'PL',
                    'alpha3' => 'POL',
                    'langEN' => 'Poland',
                    'langIT' => 'Polonia',
                    'nazionalitaEN' => 'Polish',
                    'nazionalitaIT' => 'Polacco',
                    'bandiera' => 'poland.svg',
                ),
            174 =>
                array(
                    'alpha2' => 'PM',
                    'alpha3' => 'SPM',
                    'langEN' => 'Saint-Pierre and Miquelon',
                    'langIT' => 'Saint Pierre e Miquelon',
                    'nazionalitaEN' => 'Saint-Pierrais or Miquelonnais',
                    'nazionalitaIT' => 'Saint-Pierrais o Miquelonnais',
                    'bandiera' => 'france.svg',
                ),
            175 =>
                array(
                    'alpha2' => 'PN',
                    'alpha3' => 'PCN',
                    'langEN' => 'Pitcairn',
                    'langIT' => 'Pitcairn',
                    'nazionalitaEN' => 'Pitcairn Island',
                    'nazionalitaIT' => 'Isola Pitcairn',
                    'bandiera' => 'pitcairn-islands.svg',
                ),
            176 =>
                array(
                    'alpha2' => 'PR',
                    'alpha3' => 'PRI',
                    'langEN' => 'Puerto Rico',
                    'langIT' => 'Porto Rico',
                    'nazionalitaEN' => 'Puerto Rican',
                    'nazionalitaIT' => 'Portoricano',
                    'bandiera' => 'puerto-rico.svg',
                ),
            177 =>
                array(
                    'alpha2' => 'PS',
                    'alpha3' => 'PSE',
                    'langEN' => 'Occupied Palestinian Territory',
                    'langIT' => 'Territori Palestinesi Occupati',
                    'nazionalitaEN' => 'Palestinian',
                    'nazionalitaIT' => 'Palestinese',
                    'bandiera' => 'palestine.svg',
                ),
            178 =>
                array(
                    'alpha2' => 'PT',
                    'alpha3' => 'PRT',
                    'langEN' => 'Portugal',
                    'langIT' => 'Portogallo',
                    'nazionalitaEN' => 'Portuguese',
                    'nazionalitaIT' => 'Portoghese',
                    'bandiera' => 'portugal.svg',
                ),
            179 =>
                array(
                    'alpha2' => 'PW',
                    'alpha3' => 'PLW',
                    'langEN' => 'Palau',
                    'langIT' => 'Palau',
                    'nazionalitaEN' => 'Palauan',
                    'nazionalitaIT' => 'Palauan',
                    'bandiera' => 'palau.svg',
                ),
            180 =>
                array(
                    'alpha2' => 'PY',
                    'alpha3' => 'PRY',
                    'langEN' => 'Paraguay',
                    'langIT' => 'Paraguay',
                    'nazionalitaEN' => 'Paraguayan',
                    'nazionalitaIT' => 'Paraguaiano',
                    'bandiera' => 'paraguay.svg',
                ),
            181 =>
                array(
                    'alpha2' => 'QA',
                    'alpha3' => 'QAT',
                    'langEN' => 'Qatar',
                    'langIT' => 'Qatar',
                    'nazionalitaEN' => 'Qatari',
                    'nazionalitaIT' => 'Del Qatar',
                    'bandiera' => 'qatar.svg',
                ),
            182 =>
                array(
                    'alpha2' => 'RE',
                    'alpha3' => 'REU',
                    'langEN' => 'Réunion',
                    'langIT' => 'Reunion',
                    'nazionalitaEN' => 'Réunionese, Réunionnais',
                    'nazionalitaIT' => 'Riunione, Riunione',
                    'bandiera' => 'france.svg',
                ),
            183 =>
                array(
                    'alpha2' => 'RO',
                    'alpha3' => 'ROU',
                    'langEN' => 'Romania',
                    'langIT' => 'Romania',
                    'nazionalitaEN' => 'Romanian',
                    'nazionalitaIT' => 'Rumeno',
                    'bandiera' => 'romania.svg',
                ),
            184 =>
                array(
                    'alpha2' => 'RU',
                    'alpha3' => 'RUS',
                    'langEN' => 'Russian Federation',
                    'langIT' => 'Federazione Russa',
                    'nazionalitaEN' => 'Russian',
                    'nazionalitaIT' => 'Russo',
                    'bandiera' => 'russia.svg',
                ),
            185 =>
                array(
                    'alpha2' => 'RW',
                    'alpha3' => 'RWA',
                    'langEN' => 'Rwanda',
                    'langIT' => 'Ruanda',
                    'nazionalitaEN' => 'Rwandan',
                    'nazionalitaIT' => 'Ruanda',
                    'bandiera' => 'rwanda.svg',
                ),
            186 =>
                array(
                    'alpha2' => 'SA',
                    'alpha3' => 'SAU',
                    'langEN' => 'Saudi Arabia',
                    'langIT' => 'Arabia Saudita',
                    'nazionalitaEN' => 'Saudi, Saudi Arabian',
                    'nazionalitaIT' => 'Saudita, Arabia Saudita',
                    'bandiera' => 'saudi-arabia.svg',
                ),
            187 =>
                array(
                    'alpha2' => 'SB',
                    'alpha3' => 'SLB',
                    'langEN' => 'Solomon Islands',
                    'langIT' => 'Isole Solomon',
                    'nazionalitaEN' => 'Solomon Island',
                    'nazionalitaIT' => 'Isole Salomone',
                    'bandiera' => 'solomon-islands.svg',
                ),
            188 =>
                array(
                    'alpha2' => 'SC',
                    'alpha3' => 'SYC',
                    'langEN' => 'Seychelles',
                    'langIT' => 'Seychelles',
                    'nazionalitaEN' => 'Seychellois',
                    'nazionalitaIT' => 'Seychelles',
                    'bandiera' => 'seychelles.svg',
                ),
            189 =>
                array(
                    'alpha2' => 'SD',
                    'alpha3' => 'SDN',
                    'langEN' => 'Sudan',
                    'langIT' => 'Sudan',
                    'nazionalitaEN' => 'Sudanese',
                    'nazionalitaIT' => 'Sudanese',
                    'bandiera' => 'sudan.svg',
                ),
            190 =>
                array(
                    'alpha2' => 'SE',
                    'alpha3' => 'SWE',
                    'langEN' => 'Sweden',
                    'langIT' => 'Svezia',
                    'nazionalitaEN' => 'Swedish',
                    'nazionalitaIT' => 'Svedese',
                    'bandiera' => 'sweden.svg',
                ),
            191 =>
                array(
                    'alpha2' => 'SG',
                    'alpha3' => 'SGP',
                    'langEN' => 'Singapore',
                    'langIT' => 'Singapore',
                    'nazionalitaEN' => 'Singaporean',
                    'nazionalitaIT' => 'Di Singapore',
                    'bandiera' => 'singapore.svg',
                ),
            192 =>
                array(
                    'alpha2' => 'SH',
                    'alpha3' => 'SHN',
                    'langEN' => 'Saint Helena',
                    'langIT' => 'Sant\'Elena',
                    'nazionalitaEN' => 'Saint Helenian',
                    'nazionalitaIT' => 'Sant\'Elena',
                    'bandiera' => 'saint-helena.svg',
                ),
            193 =>
                array(
                    'alpha2' => 'SI',
                    'alpha3' => 'SVN',
                    'langEN' => 'Slovenia',
                    'langIT' => 'Slovenia',
                    'nazionalitaEN' => 'Slovenian, Slovene',
                    'nazionalitaIT' => 'Sloveno, sloveno',
                    'bandiera' => 'slovenia.svg',
                ),
            194 =>
                array(
                    'alpha2' => 'SJ',
                    'alpha3' => 'SJM',
                    'langEN' => 'Svalbard and Jan Mayen',
                    'langIT' => 'Svalbard e Jan Mayen',
                    'nazionalitaEN' => 'Svalbard',
                    'nazionalitaIT' => 'Svalbard',
                    'bandiera' => 'flag.svg',
                ),
            195 =>
                array(
                    'alpha2' => 'SK',
                    'alpha3' => 'SVK',
                    'langEN' => 'Slovakia',
                    'langIT' => 'Slovacchia',
                    'nazionalitaEN' => 'Slovak',
                    'nazionalitaIT' => 'Slovacco',
                    'bandiera' => 'slovakia.svg',
                ),
            196 =>
                array(
                    'alpha2' => 'SL',
                    'alpha3' => 'SLE',
                    'langEN' => 'Sierra Leone',
                    'langIT' => 'Sierra Leone',
                    'nazionalitaEN' => 'Sierra Leonean',
                    'nazionalitaIT' => 'Sierra Leone',
                    'bandiera' => 'sierra-leone.svg',
                ),
            197 =>
                array(
                    'alpha2' => 'SM',
                    'alpha3' => 'SMR',
                    'langEN' => 'San Marino',
                    'langIT' => 'San Marino',
                    'nazionalitaEN' => 'Sammarinese',
                    'nazionalitaIT' => 'Sammarinese',
                    'bandiera' => 'san-marino.svg',
                ),
            198 =>
                array(
                    'alpha2' => 'SN',
                    'alpha3' => 'SEN',
                    'langEN' => 'Senegal',
                    'langIT' => 'Senegal',
                    'nazionalitaEN' => 'Senegalese',
                    'nazionalitaIT' => 'Senegalese',
                    'bandiera' => 'senegal.svg',
                ),
            199 =>
                array(
                    'alpha2' => 'SO',
                    'alpha3' => 'SOM',
                    'langEN' => 'Somalia',
                    'langIT' => 'Somalia',
                    'nazionalitaEN' => 'Somali, Somalian',
                    'nazionalitaIT' => 'Somalo, somalo',
                    'bandiera' => 'somalia.svg',
                ),
            200 =>
                array(
                    'alpha2' => 'SR',
                    'alpha3' => 'SUR',
                    'langEN' => 'Suriname',
                    'langIT' => 'Suriname',
                    'nazionalitaEN' => 'Surinamese',
                    'nazionalitaIT' => 'Suriname',
                    'bandiera' => 'suriname.svg',
                ),
            201 =>
                array(
                    'alpha2' => 'SS',
                    'alpha3' => 'SSD',
                    'langEN' => 'South Sudan',
                    'langIT' => 'Sudan del Sud',
                    'nazionalitaEN' => 'South Sudanese',
                    'nazionalitaIT' => 'Sud Sudan',
                    'bandiera' => 'south-sudan.svg',
                ),
            202 =>
                array(
                    'alpha2' => 'ST',
                    'alpha3' => 'STP',
                    'langEN' => 'Sao Tome and Principe',
                    'langIT' => 'Sao Tome e Principe',
                    'nazionalitaEN' => 'São Toméan',
                    'nazionalitaIT' => 'San Tommaso',
                    'bandiera' => 'sao-tome-and-prince.svg',
                ),
            203 =>
                array(
                    'alpha2' => 'SV',
                    'alpha3' => 'SLV',
                    'langEN' => 'El Salvador',
                    'langIT' => 'El Salvador',
                    'nazionalitaEN' => 'Salvadoran',
                    'nazionalitaIT' => 'Salvadoregno',
                    'bandiera' => 'el-salvador.svg',
                ),
            204 =>
                array(
                    'alpha2' => 'SY',
                    'alpha3' => 'SYR',
                    'langEN' => 'Syrian Arab Republic',
                    'langIT' => 'Siria',
                    'nazionalitaEN' => 'Syrian',
                    'nazionalitaIT' => 'Siriano',
                    'bandiera' => 'syria.svg',
                ),
            205 =>
                array(
                    'alpha2' => 'SZ',
                    'alpha3' => 'SWZ',
                    'langEN' => 'Swaziland',
                    'langIT' => 'Swaziland',
                    'nazionalitaEN' => 'Swazi',
                    'nazionalitaIT' => 'Swazi',
                    'bandiera' => 'swaziland.svg',
                ),
            206 =>
                array(
                    'alpha2' => 'TC',
                    'alpha3' => 'TCA',
                    'langEN' => 'Turks and Caicos Islands',
                    'langIT' => 'Isole Turks e Caicos',
                    'nazionalitaEN' => 'Turks and Caicos Island',
                    'nazionalitaIT' => 'Isole Turks e Caicos',
                    'bandiera' => 'turks-and-caicos.svg',
                ),
            207 =>
                array(
                    'alpha2' => 'TD',
                    'alpha3' => 'TCD',
                    'langEN' => 'Chad',
                    'langIT' => 'Ciad',
                    'nazionalitaEN' => 'Chadian',
                    'nazionalitaIT' => 'Ciadiano',
                    'bandiera' => 'chad.svg',
                ),
            208 =>
                array(
                    'alpha2' => 'TF',
                    'alpha3' => 'ATF',
                    'langEN' => 'French Southern Territories',
                    'langIT' => 'Territori Francesi del Sud',
                    'nazionalitaEN' => 'French Southern Territories',
                    'nazionalitaIT' => 'Territori della Francia del sud',
                    'bandiera' => 'france.svg',
                ),
            209 =>
                array(
                    'alpha2' => 'TG',
                    'alpha3' => 'TGO',
                    'langEN' => 'Togo',
                    'langIT' => 'Togo',
                    'nazionalitaEN' => 'Togolese',
                    'nazionalitaIT' => 'Togolese',
                    'bandiera' => 'togo.svg',
                ),
            210 =>
                array(
                    'alpha2' => 'TH',
                    'alpha3' => 'THA',
                    'langEN' => 'Thailand',
                    'langIT' => 'Tailandia',
                    'nazionalitaEN' => 'Thai',
                    'nazionalitaIT' => 'Tailandese',
                    'bandiera' => 'thailand.svg',
                ),
            211 =>
                array(
                    'alpha2' => 'TJ',
                    'alpha3' => 'TJK',
                    'langEN' => 'Tajikistan',
                    'langIT' => 'Tagikistan',
                    'nazionalitaEN' => 'Tajikistani',
                    'nazionalitaIT' => 'Tagikistan',
                    'bandiera' => 'tajikistan.svg',
                ),
            212 =>
                array(
                    'alpha2' => 'TK',
                    'alpha3' => 'TKL',
                    'langEN' => 'Tokelau',
                    'langIT' => 'Tokelau',
                    'nazionalitaEN' => 'Tokelauan',
                    'nazionalitaIT' => 'Tokelauan',
                    'bandiera' => 'tokelau.svg',
                ),
            213 =>
                array(
                    'alpha2' => 'TL',
                    'alpha3' => 'TLS',
                    'langEN' => 'Timor-Leste',
                    'langIT' => 'Timor Est',
                    'nazionalitaEN' => 'Timorese',
                    'nazionalitaIT' => 'Timorese',
                    'bandiera' => 'east-timor.svg',
                ),
            214 =>
                array(
                    'alpha2' => 'TM',
                    'alpha3' => 'TKM',
                    'langEN' => 'Turkmenistan',
                    'langIT' => 'Turkmenistan',
                    'nazionalitaEN' => 'Turkmen',
                    'nazionalitaIT' => 'Turkmeno',
                    'bandiera' => 'turkmenistan.svg',
                ),
            215 =>
                array(
                    'alpha2' => 'TN',
                    'alpha3' => 'TUN',
                    'langEN' => 'Tunisia',
                    'langIT' => 'Tunisia',
                    'nazionalitaEN' => 'Tunisian',
                    'nazionalitaIT' => 'Tunisino',
                    'bandiera' => 'tunisia.svg',
                ),
            216 =>
                array(
                    'alpha2' => 'TO',
                    'alpha3' => 'TON',
                    'langEN' => 'Tonga',
                    'langIT' => 'Tonga',
                    'nazionalitaEN' => 'Tongan',
                    'nazionalitaIT' => 'Tongano',
                    'bandiera' => 'tonga.svg',
                ),
            217 =>
                array(
                    'alpha2' => 'TR',
                    'alpha3' => 'TUR',
                    'langEN' => 'Turkey',
                    'langIT' => 'Turchia',
                    'nazionalitaEN' => 'Turkish',
                    'nazionalitaIT' => 'Turco',
                    'bandiera' => 'turkey.svg',
                ),
            218 =>
                array(
                    'alpha2' => 'TT',
                    'alpha3' => 'TTO',
                    'langEN' => 'Trinidad and Tobago',
                    'langIT' => 'Trinidad e Tobago',
                    'nazionalitaEN' => 'Trinidadian or Tobagonian',
                    'nazionalitaIT' => 'Trinidad e Tobagonian',
                    'bandiera' => 'trinidad-and-tobago.svg',
                ),
            219 =>
                array(
                    'alpha2' => 'TV',
                    'alpha3' => 'TUV',
                    'langEN' => 'Tuvalu',
                    'langIT' => 'Tuvalu',
                    'nazionalitaEN' => 'Tuvaluan',
                    'nazionalitaIT' => 'Tuvaluano',
                    'bandiera' => 'tuvalu.svg',
                ),
            220 =>
                array(
                    'alpha2' => 'TW',
                    'alpha3' => 'TWN',
                    'langEN' => 'Taiwan',
                    'langIT' => 'Taiwan',
                    'nazionalitaEN' => 'Chinese, Taiwanese',
                    'nazionalitaIT' => 'Cinese, taiwanese',
                    'bandiera' => 'taiwan.svg',
                ),
            221 =>
                array(
                    'alpha2' => 'TZ',
                    'alpha3' => 'TZA',
                    'langEN' => 'United Republic Of Tanzania',
                    'langIT' => 'Tanzania',
                    'nazionalitaEN' => 'Tanzanian',
                    'nazionalitaIT' => 'Tanzaniano',
                    'bandiera' => 'tanzania.svg',
                ),
            222 =>
                array(
                    'alpha2' => 'UA',
                    'alpha3' => 'UKR',
                    'langEN' => 'Ukraine',
                    'langIT' => 'Ucraina',
                    'nazionalitaEN' => 'Ukrainian',
                    'nazionalitaIT' => 'Ucraino',
                    'bandiera' => 'ukraine.svg',
                ),
            223 =>
                array(
                    'alpha2' => 'UG',
                    'alpha3' => 'UGA',
                    'langEN' => 'Uganda',
                    'langIT' => 'Uganda',
                    'nazionalitaEN' => 'Ugandan',
                    'nazionalitaIT' => 'Ugandese',
                    'bandiera' => 'uganda.svg',
                ),
            224 =>
                array(
                    'alpha2' => 'UM',
                    'alpha3' => 'UMI',
                    'langEN' => 'United States Minor Outlying Islands',
                    'langIT' => 'Isole Minori degli Stati Uniti d\'America',
                    'nazionalitaEN' => 'American',
                    'nazionalitaIT' => 'Americano',
                    'bandiera' => 'united-states.svg',
                ),
            225 =>
                array(
                    'alpha2' => 'US',
                    'alpha3' => 'USA',
                    'langEN' => 'United States',
                    'langIT' => 'Stati Uniti d\'America',
                    'nazionalitaEN' => 'American',
                    'nazionalitaIT' => 'Americano',
                    'bandiera' => 'united-states.svg',
                ),
            226 =>
                array(
                    'alpha2' => 'UY',
                    'alpha3' => 'URY',
                    'langEN' => 'Uruguay',
                    'langIT' => 'Uruguay',
                    'nazionalitaEN' => 'Uruguayan',
                    'nazionalitaIT' => 'Uruguaiano',
                    'bandiera' => 'uruguay.svg',
                ),
            227 =>
                array(
                    'alpha2' => 'UZ',
                    'alpha3' => 'UZB',
                    'langEN' => 'Uzbekistan',
                    'langIT' => 'Uzbekistan',
                    'nazionalitaEN' => 'Uzbekistani, Uzbek',
                    'nazionalitaIT' => 'Uzbeko, uzbeko',
                    'bandiera' => 'uzbekistan.svg',
                ),
            228 =>
                array(
                    'alpha2' => 'VA',
                    'alpha3' => 'VAT',
                    'langEN' => 'Vatican City State',
                    'langIT' => 'Città del Vaticano',
                    'nazionalitaEN' => 'Vatican',
                    'nazionalitaIT' => 'Vaticano',
                    'bandiera' => 'vatican-city.svg',
                ),
            229 =>
                array(
                    'alpha2' => 'VC',
                    'alpha3' => 'VCT',
                    'langEN' => 'Saint Vincent and the Grenadines',
                    'langIT' => 'Saint Vincent e Grenadine',
                    'nazionalitaEN' => 'Saint Vincentian, Vincentian',
                    'nazionalitaIT' => 'San Vincenzo, Vincenziano',
                    'bandiera' => 'st-vincent-and-the-grenadines.svg',
                ),
            230 =>
                array(
                    'alpha2' => 'VE',
                    'alpha3' => 'VEN',
                    'langEN' => 'Venezuela',
                    'langIT' => 'Venezuela',
                    'nazionalitaEN' => 'Venezuelan',
                    'nazionalitaIT' => 'Venezuelano',
                    'bandiera' => 'venezuela.svg',
                ),
            231 =>
                array(
                    'alpha2' => 'VG',
                    'alpha3' => 'VGB',
                    'langEN' => 'British Virgin Islands',
                    'langIT' => 'Isole Vergini Britanniche',
                    'nazionalitaEN' => 'British Virgin Island',
                    'nazionalitaIT' => 'Isole Vergini Britanniche',
                    'bandiera' => 'british-virgin-islands.svg',
                ),
            232 =>
                array(
                    'alpha2' => 'VI',
                    'alpha3' => 'VIR',
                    'langEN' => 'U.S. Virgin Islands',
                    'langIT' => 'Isole Vergini Americane',
                    'nazionalitaEN' => 'U.S. Virgin Island',
                    'nazionalitaIT' => 'Isole Vergini americane',
                    'bandiera' => 'virgin-islands.svg',
                ),
            233 =>
                array(
                    'alpha2' => 'VN',
                    'alpha3' => 'VNM',
                    'langEN' => 'Vietnam',
                    'langIT' => 'Vietnam',
                    'nazionalitaEN' => 'Vietnamese',
                    'nazionalitaIT' => 'Vietnamita',
                    'bandiera' => 'vietnam.svg',
                ),
            234 =>
                array(
                    'alpha2' => 'VU',
                    'alpha3' => 'VUT',
                    'langEN' => 'Vanuatu',
                    'langIT' => 'Vanuatu',
                    'nazionalitaEN' => 'Ni-Vanuatu, Vanuatuan',
                    'nazionalitaIT' => 'Ni-Vanuatu, Vanuatuan',
                    'bandiera' => 'vanuatu.svg',
                ),
            235 =>
                array(
                    'alpha2' => 'WF',
                    'alpha3' => 'WLF',
                    'langEN' => 'Wallis and Futuna',
                    'langIT' => 'Wallis e Futuna',
                    'nazionalitaEN' => 'Wallis and Futuna, Wallisian or Futunan',
                    'nazionalitaIT' => 'Wallis e Futuna, Wallisian o Futunan',
                    'bandiera' => 'france.svg',
                ),
            236 =>
                array(
                    'alpha2' => 'WS',
                    'alpha3' => 'WSM',
                    'langEN' => 'Samoa',
                    'langIT' => 'Samoa',
                    'nazionalitaEN' => 'Samoan',
                    'nazionalitaIT' => 'Samoano',
                    'bandiera' => 'samoa.svg',
                ),
            237 =>
                array(
                    'alpha2' => 'YE',
                    'alpha3' => 'YEM',
                    'langEN' => 'Yemen',
                    'langIT' => 'Yemen',
                    'nazionalitaEN' => 'Yemeni',
                    'nazionalitaIT' => 'Yemenita',
                    'bandiera' => 'yemen.svg',
                ),
            238 =>
                array(
                    'alpha2' => 'YT',
                    'alpha3' => 'MYT',
                    'langEN' => 'Mayotte',
                    'langIT' => 'Mayotte',
                    'nazionalitaEN' => 'Mahoran',
                    'nazionalitaIT' => 'Pezzi',
                    'bandiera' => 'france.svg',
                ),
            239 =>
                array(
                    'alpha2' => 'ZA',
                    'alpha3' => 'ZAF',
                    'langEN' => 'South Africa',
                    'langIT' => 'Sud Africa',
                    'nazionalitaEN' => 'South African',
                    'nazionalitaIT' => 'Sudafricano',
                    'bandiera' => 'south-africa.svg',
                ),
            240 =>
                array(
                    'alpha2' => 'ZM',
                    'alpha3' => 'ZMB',
                    'langEN' => 'Zambia',
                    'langIT' => 'Zambia',
                    'nazionalitaEN' => 'Zambian',
                    'nazionalitaIT' => 'Zambiano',
                    'bandiera' => 'zambia.svg',
                ),
            241 =>
                array(
                    'alpha2' => 'ZW',
                    'alpha3' => 'ZWE',
                    'langEN' => 'Zimbabwe',
                    'langIT' => 'Zimbabwe',
                    'nazionalitaEN' => 'Zimbabwean',
                    'nazionalitaIT' => 'Dello Zimbabwe',
                    'bandiera' => 'zimbabwe.svg',
                ),
        ));


    }
}
