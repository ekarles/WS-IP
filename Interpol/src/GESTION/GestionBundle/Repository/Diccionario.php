<?php

namespace GESTION\GestionBundle\Repository;

/**
 * Description of Interpol
 *
 * @author pfa27667140
 */
class Diccionario{
    private $Paises = Array(
        '100' => Array('ID' => '100', 'COD_A' => 'AD', 'COD_B' => 'AND', 'DESCRIPCION' => 'Andorra'),
        '101' => Array('ID' => '101', 'COD_A' => 'AE', 'COD_B' => 'ARE', 'DESCRIPCION' => 'Emiratos Árabes Unidos'),
        '102' => Array('ID' => '102', 'COD_A' => 'AF', 'COD_B' => 'AFG', 'DESCRIPCION' => 'Afganistán'),
        '103' => Array('ID' => '103', 'COD_A' => 'AG', 'COD_B' => 'ATG', 'DESCRIPCION' => 'Antigua y Barbuda'),
        '104' => Array('ID' => '104', 'COD_A' => 'AI', 'COD_B' => 'AIA', 'DESCRIPCION' => 'Anguila'),
        '105' => Array('ID' => '105', 'COD_A' => 'AL', 'COD_B' => 'ALB', 'DESCRIPCION' => 'Albania'),
        '106' => Array('ID' => '106', 'COD_A' => 'AM', 'COD_B' => 'ARM', 'DESCRIPCION' => 'Armenia'),
        '108' => Array('ID' => '108', 'COD_A' => 'AO', 'COD_B' => 'AGO', 'DESCRIPCION' => 'Angola'),
        '109' => Array('ID' => '109', 'COD_A' => 'AQ', 'COD_B' => 'ATA', 'DESCRIPCION' => 'Antártida'),
        '110' => Array('ID' => '110', 'COD_A' => 'AR', 'COD_B' => 'ARG', 'DESCRIPCION' => 'Argentina'),
        '111' => Array('ID' => '111', 'COD_A' => 'AS', 'COD_B' => 'ASM', 'DESCRIPCION' => 'Samoa Americana'),
        '112' => Array('ID' => '112', 'COD_A' => 'AT', 'COD_B' => 'AUT', 'DESCRIPCION' => 'Austria'),
        '113' => Array('ID' => '113', 'COD_A' => 'AU', 'COD_B' => 'AUS', 'DESCRIPCION' => 'Australia'),
        '114' => Array('ID' => '114', 'COD_A' => 'AW', 'COD_B' => 'ABW', 'DESCRIPCION' => 'Aruba'),
        '115' => Array('ID' => '115', 'COD_A' => 'AX', 'COD_B' => 'ALA', 'DESCRIPCION' => 'Åland'),
        '116' => Array('ID' => '116', 'COD_A' => 'AZ', 'COD_B' => 'AZE', 'DESCRIPCION' => 'Azerbaiyán'),
        '117' => Array('ID' => '117', 'COD_A' => 'BA', 'COD_B' => 'BIH', 'DESCRIPCION' => 'Bosnia y Herzegovina'),
        '118' => Array('ID' => '118', 'COD_A' => 'BB', 'COD_B' => 'BRB', 'DESCRIPCION' => 'Barbados'),
        '119' => Array('ID' => '119', 'COD_A' => 'BD', 'COD_B' => 'BGD', 'DESCRIPCION' => 'Bangladés'),
        '120' => Array('ID' => '120', 'COD_A' => 'BE', 'COD_B' => 'BEL', 'DESCRIPCION' => 'Bélgica'),
        '121' => Array('ID' => '121', 'COD_A' => 'BF', 'COD_B' => 'BFA', 'DESCRIPCION' => 'Burkina Faso'),
        '122' => Array('ID' => '122', 'COD_A' => 'BG', 'COD_B' => 'BGR', 'DESCRIPCION' => 'Bulgaria'),
        '123' => Array('ID' => '123', 'COD_A' => 'BH', 'COD_B' => 'BHR', 'DESCRIPCION' => 'Baréin'),
        '124' => Array('ID' => '124', 'COD_A' => 'BI', 'COD_B' => 'BDI', 'DESCRIPCION' => 'Burundi'),
        '125' => Array('ID' => '125', 'COD_A' => 'BJ', 'COD_B' => 'BEN', 'DESCRIPCION' => 'Benín'),
        '126' => Array('ID' => '126', 'COD_A' => 'BM', 'COD_B' => 'BMU', 'DESCRIPCION' => 'Bermudas'),
        '127' => Array('ID' => '127', 'COD_A' => 'BN', 'COD_B' => 'BRN', 'DESCRIPCION' => 'Brunéi'),
        '128' => Array('ID' => '128', 'COD_A' => 'BO', 'COD_B' => 'BOL', 'DESCRIPCION' => 'Bolivia'),
        '129' => Array('ID' => '129', 'COD_A' => 'BR', 'COD_B' => 'BRA', 'DESCRIPCION' => 'Brasil'),
        '130' => Array('ID' => '130', 'COD_A' => 'BS', 'COD_B' => 'BHS', 'DESCRIPCION' => 'Bahamas'),
        '131' => Array('ID' => '131', 'COD_A' => 'BT', 'COD_B' => 'BTN', 'DESCRIPCION' => 'Bután'),
        '132' => Array('ID' => '132', 'COD_A' => 'BV', 'COD_B' => 'BVT', 'DESCRIPCION' => 'Isla Bouvet'),
        '133' => Array('ID' => '133', 'COD_A' => 'BW', 'COD_B' => 'BWA', 'DESCRIPCION' => 'Botsuana'),
        '134' => Array('ID' => '134', 'COD_A' => 'BY', 'COD_B' => 'BLR', 'DESCRIPCION' => 'Bielorrusia'),
        '135' => Array('ID' => '135', 'COD_A' => 'BZ', 'COD_B' => 'BLZ', 'DESCRIPCION' => 'Belice'),
        '136' => Array('ID' => '136', 'COD_A' => 'CA', 'COD_B' => 'CAN', 'DESCRIPCION' => 'Canadá'),
        '137' => Array('ID' => '137', 'COD_A' => 'CC', 'COD_B' => 'CCK', 'DESCRIPCION' => 'Islas Cocos'),
        '138' => Array('ID' => '138', 'COD_A' => 'CD', 'COD_B' => 'COD', 'DESCRIPCION' => 'República Democrática del Congo'),
        '139' => Array('ID' => '139', 'COD_A' => 'CF', 'COD_B' => 'CAF', 'DESCRIPCION' => 'República Centroafricana'),
        '140' => Array('ID' => '140', 'COD_A' => 'CG', 'COD_B' => 'COG', 'DESCRIPCION' => 'República del Congo'),
        '141' => Array('ID' => '141', 'COD_A' => 'CH', 'COD_B' => 'CHE', 'DESCRIPCION' => 'Suiza'),
        '142' => Array('ID' => '142', 'COD_A' => 'CI', 'COD_B' => 'CIV', 'DESCRIPCION' => 'Costa de Marfil'),
        '143' => Array('ID' => '143', 'COD_A' => 'CK', 'COD_B' => 'COK', 'DESCRIPCION' => 'Islas Cook'),
        '144' => Array('ID' => '144', 'COD_A' => 'CL', 'COD_B' => 'CHL', 'DESCRIPCION' => 'Chile'),
        '145' => Array('ID' => '145', 'COD_A' => 'CM', 'COD_B' => 'CMR', 'DESCRIPCION' => 'Camerún'),
        '146' => Array('ID' => '146', 'COD_A' => 'CN', 'COD_B' => 'CHN', 'DESCRIPCION' => 'República Popular China'),
        '147' => Array('ID' => '147', 'COD_A' => 'CO', 'COD_B' => 'COL', 'DESCRIPCION' => 'Colombia'),
        '148' => Array('ID' => '148', 'COD_A' => 'CR', 'COD_B' => 'CRI', 'DESCRIPCION' => 'Costa Rica'),
        '150' => Array('ID' => '150', 'COD_A' => 'CU', 'COD_B' => 'CUB', 'DESCRIPCION' => 'Cuba'),
        '151' => Array('ID' => '151', 'COD_A' => 'CV', 'COD_B' => 'CPV', 'DESCRIPCION' => 'Cabo Verde'),
        '342' => Array('ID' => '342', 'COD_A' => 'CW', 'COD_B' => 'CUW', 'DESCRIPCION' => 'Curazao'),
        '152' => Array('ID' => '152', 'COD_A' => 'CX', 'COD_B' => 'CXR', 'DESCRIPCION' => 'Isla de Navidad'),
        '153' => Array('ID' => '153', 'COD_A' => 'CY', 'COD_B' => 'CYP', 'DESCRIPCION' => 'Chipre'),
        '154' => Array('ID' => '154', 'COD_A' => 'CZ', 'COD_B' => 'CZE', 'DESCRIPCION' => 'República Checa'),
        '155' => Array('ID' => '155', 'COD_A' => 'D' , 'COD_B' => 'DE' , 'DESCRIPCION' => 'Alemania'),
        '156' => Array('ID' => '156', 'COD_A' => 'DJ', 'COD_B' => 'DJI', 'DESCRIPCION' => 'Yibuti'),
        '157' => Array('ID' => '157', 'COD_A' => 'DK', 'COD_B' => 'DNK', 'DESCRIPCION' => 'Dinamarca'),
        '158' => Array('ID' => '158', 'COD_A' => 'DM', 'COD_B' => 'DMA', 'DESCRIPCION' => 'Dominica'),
        '159' => Array('ID' => '159', 'COD_A' => 'DO', 'COD_B' => 'DOM', 'DESCRIPCION' => 'República Dominicana'),
        '160' => Array('ID' => '160', 'COD_A' => 'DZ', 'COD_B' => 'DZA', 'DESCRIPCION' => 'Argelia'),
        '161' => Array('ID' => '161', 'COD_A' => 'EC', 'COD_B' => 'ECU', 'DESCRIPCION' => 'Ecuador'),
        '162' => Array('ID' => '162', 'COD_A' => 'EE', 'COD_B' => 'EST', 'DESCRIPCION' => 'Estonia'),
        '163' => Array('ID' => '163', 'COD_A' => 'EG', 'COD_B' => 'EGY', 'DESCRIPCION' => 'Egipto'),
        '164' => Array('ID' => '164', 'COD_A' => 'EH', 'COD_B' => 'ESH', 'DESCRIPCION' => 'Sahara Occidental'),
        '165' => Array('ID' => '165', 'COD_A' => 'ER', 'COD_B' => 'ERI', 'DESCRIPCION' => 'Eritrea'),
        '166' => Array('ID' => '166', 'COD_A' => 'ES', 'COD_B' => 'ESP', 'DESCRIPCION' => 'España'),
        '167' => Array('ID' => '167', 'COD_A' => 'ET', 'COD_B' => 'ETH', 'DESCRIPCION' => 'Etiopía'),
        '168' => Array('ID' => '168', 'COD_A' => 'FI', 'COD_B' => 'FIN', 'DESCRIPCION' => 'Finlandia'),
        '169' => Array('ID' => '169', 'COD_A' => 'FJ', 'COD_B' => 'FJI', 'DESCRIPCION' => 'Fiyi'),
        '170' => Array('ID' => '170', 'COD_A' => 'FK', 'COD_B' => 'FLK', 'DESCRIPCION' => 'Islas Malvinas'),
        '171' => Array('ID' => '171', 'COD_A' => 'FM', 'COD_B' => 'FSM', 'DESCRIPCION' => 'Micronesia'),
        '172' => Array('ID' => '172', 'COD_A' => 'FO', 'COD_B' => 'FRO', 'DESCRIPCION' => 'Islas Feroe'),
        '173' => Array('ID' => '173', 'COD_A' => 'FR', 'COD_B' => 'FRA', 'DESCRIPCION' => 'Francia'),
        '174' => Array('ID' => '174', 'COD_A' => 'GA', 'COD_B' => 'GAB', 'DESCRIPCION' => 'Gabón'),
        '175' => Array('ID' => '175', 'COD_A' => 'GB', 'COD_B' => 'GBR', 'DESCRIPCION' => 'Reino Unido'),
        '176' => Array('ID' => '176', 'COD_A' => 'GD', 'COD_B' => 'GRD', 'DESCRIPCION' => 'Granada'),
        '177' => Array('ID' => '177', 'COD_A' => 'GE', 'COD_B' => 'GEO', 'DESCRIPCION' => 'Georgia'),
        '178' => Array('ID' => '178', 'COD_A' => 'GF', 'COD_B' => 'GUF', 'DESCRIPCION' => 'Guayana Francesa'),
        '179' => Array('ID' => '179', 'COD_A' => 'GH', 'COD_B' => 'GHA', 'DESCRIPCION' => 'Ghana'),
        '180' => Array('ID' => '180', 'COD_A' => 'GI', 'COD_B' => 'GIB', 'DESCRIPCION' => 'Gibraltar'),
        '181' => Array('ID' => '181', 'COD_A' => 'GL', 'COD_B' => 'GRL', 'DESCRIPCION' => 'Groenlandia'),
        '182' => Array('ID' => '182', 'COD_A' => 'GM', 'COD_B' => 'GMB', 'DESCRIPCION' => 'Gambia'),
        '183' => Array('ID' => '183', 'COD_A' => 'GN', 'COD_B' => 'GNB', 'DESCRIPCION' => 'Guinea'),
        '184' => Array('ID' => '184', 'COD_A' => 'GP', 'COD_B' => 'GLP', 'DESCRIPCION' => 'Guadalupe'),
        '185' => Array('ID' => '185', 'COD_A' => 'GQ', 'COD_B' => 'GNQ', 'DESCRIPCION' => 'Guinea Ecuatorial'),
        '186' => Array('ID' => '186', 'COD_A' => 'GR', 'COD_B' => 'GRC', 'DESCRIPCION' => 'Grecia'),
        '187' => Array('ID' => '187', 'COD_A' => 'GS', 'COD_B' => 'SGS', 'DESCRIPCION' => 'Islas Georgias del Sur y Sandwich del Sur'),
        '188' => Array('ID' => '188', 'COD_A' => 'GT', 'COD_B' => 'GTM', 'DESCRIPCION' => 'Guatemala'),
        '189' => Array('ID' => '189', 'COD_A' => 'GU', 'COD_B' => 'GUM', 'DESCRIPCION' => 'Guam'),
        '190' => Array('ID' => '190', 'COD_A' => 'GW', 'COD_B' => 'GIN', 'DESCRIPCION' => 'Guinea-Bisáu'),
        '191' => Array('ID' => '191', 'COD_A' => 'GY', 'COD_B' => 'GUY', 'DESCRIPCION' => 'Guyana'),
        '192' => Array('ID' => '192', 'COD_A' => 'HK', 'COD_B' => 'HKG', 'DESCRIPCION' => 'Hong Kong'),
        '193' => Array('ID' => '193', 'COD_A' => 'HM', 'COD_B' => 'HMD', 'DESCRIPCION' => 'Islas Heard y McDonald'),
        '194' => Array('ID' => '194', 'COD_A' => 'HN', 'COD_B' => 'HND', 'DESCRIPCION' => 'Honduras'),
        '195' => Array('ID' => '195', 'COD_A' => 'HR', 'COD_B' => 'HRV', 'DESCRIPCION' => 'Croacia'),
        '196' => Array('ID' => '196', 'COD_A' => 'HT', 'COD_B' => 'HTI', 'DESCRIPCION' => 'Haití'),
        '197' => Array('ID' => '197', 'COD_A' => 'HU', 'COD_B' => 'HUN', 'DESCRIPCION' => 'Hungría'),
        '198' => Array('ID' => '198', 'COD_A' => 'ID', 'COD_B' => 'IDN', 'DESCRIPCION' => 'Indonesia'),
        '199' => Array('ID' => '199', 'COD_A' => 'IE', 'COD_B' => 'IRL', 'DESCRIPCION' => 'Irlanda'),
        '200' => Array('ID' => '200', 'COD_A' => 'IL', 'COD_B' => 'ISR', 'DESCRIPCION' => 'Israel'),
        '201' => Array('ID' => '201', 'COD_A' => 'IN', 'COD_B' => 'IND', 'DESCRIPCION' => 'India'),
        '202' => Array('ID' => '202', 'COD_A' => 'IO', 'COD_B' => 'IOT', 'DESCRIPCION' => 'Territorio Británico del Océano Índico'),
        '203' => Array('ID' => '203', 'COD_A' => 'IQ', 'COD_B' => 'IRQ', 'DESCRIPCION' => 'Irak'),
        '204' => Array('ID' => '204', 'COD_A' => 'IR', 'COD_B' => 'IRN', 'DESCRIPCION' => 'Irán'),
        '205' => Array('ID' => '205', 'COD_A' => 'IS', 'COD_B' => 'ISL', 'DESCRIPCION' => 'Islandia'),
        '206' => Array('ID' => '206', 'COD_A' => 'IT', 'COD_B' => 'ITA', 'DESCRIPCION' => 'Italia'),
        '207' => Array('ID' => '207', 'COD_A' => 'JM', 'COD_B' => 'JAM', 'DESCRIPCION' => 'Jamaica'),
        '208' => Array('ID' => '208', 'COD_A' => 'JO', 'COD_B' => 'JOR', 'DESCRIPCION' => 'Jordania'),
        '209' => Array('ID' => '209', 'COD_A' => 'JP', 'COD_B' => 'JPN', 'DESCRIPCION' => 'Japón'),
        '210' => Array('ID' => '210', 'COD_A' => 'KE', 'COD_B' => 'KEN', 'DESCRIPCION' => 'Kenia'),
        '211' => Array('ID' => '211', 'COD_A' => 'KG', 'COD_B' => 'KGZ', 'DESCRIPCION' => 'Kirguistán'),
        '212' => Array('ID' => '212', 'COD_A' => 'KH', 'COD_B' => 'KHM', 'DESCRIPCION' => 'Camboya'),
        '213' => Array('ID' => '213', 'COD_A' => 'KI', 'COD_B' => 'KIR', 'DESCRIPCION' => 'Kiribati'),
        '214' => Array('ID' => '214', 'COD_A' => 'KM', 'COD_B' => 'COM', 'DESCRIPCION' => 'Comoras'),
        '215' => Array('ID' => '215', 'COD_A' => 'KN', 'COD_B' => 'KNA', 'DESCRIPCION' => 'San Cristóbal y Nieves'),
        '216' => Array('ID' => '216', 'COD_A' => 'KR', 'COD_B' => 'PRK', 'DESCRIPCION' => 'Corea del Sur'),
        '217' => Array('ID' => '217', 'COD_A' => 'KP', 'COD_B' => 'KOR', 'DESCRIPCION' => 'Corea del Norte'),
        '218' => Array('ID' => '218', 'COD_A' => 'KW', 'COD_B' => 'KWT', 'DESCRIPCION' => 'Kuwait'),
        '219' => Array('ID' => '219', 'COD_A' => 'KY', 'COD_B' => 'CYM', 'DESCRIPCION' => 'Islas Caimán'),
        '220' => Array('ID' => '220', 'COD_A' => 'KZ', 'COD_B' => 'KAZ', 'DESCRIPCION' => 'Kazajistán'),
        '221' => Array('ID' => '221', 'COD_A' => 'LA', 'COD_B' => 'LAO', 'DESCRIPCION' => 'Laos'),
        '222' => Array('ID' => '222', 'COD_A' => 'LB', 'COD_B' => 'LBN', 'DESCRIPCION' => 'Líbano'),
        '223' => Array('ID' => '223', 'COD_A' => 'LC', 'COD_B' => 'LCA', 'DESCRIPCION' => 'Santa Lucía'),
        '224' => Array('ID' => '224', 'COD_A' => 'LI', 'COD_B' => 'LIE', 'DESCRIPCION' => 'Liechtenstein'),
        '225' => Array('ID' => '225', 'COD_A' => 'LK', 'COD_B' => 'LKA', 'DESCRIPCION' => 'Sri Lanka'),
        '226' => Array('ID' => '226', 'COD_A' => 'LR', 'COD_B' => 'LBR', 'DESCRIPCION' => 'Liberia'),
        '227' => Array('ID' => '227', 'COD_A' => 'LS', 'COD_B' => 'LSO', 'DESCRIPCION' => 'Lesoto'),
        '228' => Array('ID' => '228', 'COD_A' => 'LT', 'COD_B' => 'LTU', 'DESCRIPCION' => 'Lituania'),
        '229' => Array('ID' => '229', 'COD_A' => 'LU', 'COD_B' => 'LUX', 'DESCRIPCION' => 'Luxemburgo'),
        '230' => Array('ID' => '230', 'COD_A' => 'LV', 'COD_B' => 'LVA', 'DESCRIPCION' => 'Letonia'),
        '231' => Array('ID' => '231', 'COD_A' => 'LY', 'COD_B' => 'LBY', 'DESCRIPCION' => 'Libia'),
        '232' => Array('ID' => '232', 'COD_A' => 'MA', 'COD_B' => 'MAR', 'DESCRIPCION' => 'Marruecos'),
        '233' => Array('ID' => '233', 'COD_A' => 'MC', 'COD_B' => 'MCO', 'DESCRIPCION' => 'Mónaco'),
        '234' => Array('ID' => '234', 'COD_A' => 'MD', 'COD_B' => 'MDA', 'DESCRIPCION' => 'Moldavia'),
        '235' => Array('ID' => '235', 'COD_A' => 'ME', 'COD_B' => 'MNE', 'DESCRIPCION' => 'Montenegro'),
        '343' => Array('ID' => '343', 'COD_A' => 'MF', 'COD_B' => 'MAF', 'DESCRIPCION' => 'San Martín'),
        '236' => Array('ID' => '236', 'COD_A' => 'MG', 'COD_B' => 'MDG', 'DESCRIPCION' => 'Madagascar'),
        '237' => Array('ID' => '237', 'COD_A' => 'MH', 'COD_B' => 'MHL', 'DESCRIPCION' => 'Islas Marshall'),
        '238' => Array('ID' => '238', 'COD_A' => 'MK', 'COD_B' => 'MKD', 'DESCRIPCION' => 'Macedonia'),
        '239' => Array('ID' => '239', 'COD_A' => 'ML', 'COD_B' => 'MLI', 'DESCRIPCION' => 'Malí'),
        '240' => Array('ID' => '240', 'COD_A' => 'MM', 'COD_B' => 'MMR', 'DESCRIPCION' => 'Myanmar'),
        '241' => Array('ID' => '241', 'COD_A' => 'MN', 'COD_B' => 'MNG', 'DESCRIPCION' => 'Mongolia'),
        '242' => Array('ID' => '242', 'COD_A' => 'MO', 'COD_B' => 'MAC', 'DESCRIPCION' => 'Macao'),
        '243' => Array('ID' => '243', 'COD_A' => 'MP', 'COD_B' => 'MNP', 'DESCRIPCION' => 'Islas Marianas del Norte'),
        '244' => Array('ID' => '244', 'COD_A' => 'MQ', 'COD_B' => 'MTQ', 'DESCRIPCION' => 'Martinica'),
        '245' => Array('ID' => '245', 'COD_A' => 'MR', 'COD_B' => 'MRT', 'DESCRIPCION' => 'Mauritania'),
        '246' => Array('ID' => '246', 'COD_A' => 'MS', 'COD_B' => 'MSR', 'DESCRIPCION' => 'Montserrat'),
        '247' => Array('ID' => '247', 'COD_A' => 'MT', 'COD_B' => 'MLT', 'DESCRIPCION' => 'Malta'),
        '248' => Array('ID' => '248', 'COD_A' => 'MU', 'COD_B' => 'MUS', 'DESCRIPCION' => 'Mauricio'),
        '249' => Array('ID' => '249', 'COD_A' => 'MV', 'COD_B' => 'MDV', 'DESCRIPCION' => 'Maldivas'),
        '250' => Array('ID' => '250', 'COD_A' => 'MW', 'COD_B' => 'MWI', 'DESCRIPCION' => 'Malaui'),
        '251' => Array('ID' => '251', 'COD_A' => 'MX', 'COD_B' => 'MEX', 'DESCRIPCION' => 'México'),
        '252' => Array('ID' => '252', 'COD_A' => 'MY', 'COD_B' => 'MYS', 'DESCRIPCION' => 'Malasia'),
        '253' => Array('ID' => '253', 'COD_A' => 'MZ', 'COD_B' => 'MOZ', 'DESCRIPCION' => 'Mozambique'),
        '254' => Array('ID' => '254', 'COD_A' => 'NA', 'COD_B' => 'NAM', 'DESCRIPCION' => 'Namibia'),
        '255' => Array('ID' => '255', 'COD_A' => 'NC', 'COD_B' => 'NCL', 'DESCRIPCION' => 'Nueva Caledonia'),
        '256' => Array('ID' => '256', 'COD_A' => 'NE', 'COD_B' => 'NER', 'DESCRIPCION' => 'Níger'),
        '257' => Array('ID' => '257', 'COD_A' => 'NF', 'COD_B' => 'NFK', 'DESCRIPCION' => 'Isla Norfolk'),
        '258' => Array('ID' => '258', 'COD_A' => 'NG', 'COD_B' => 'NGA', 'DESCRIPCION' => 'Nigeria'),
        '259' => Array('ID' => '259', 'COD_A' => 'NI', 'COD_B' => 'NIC', 'DESCRIPCION' => 'Nicaragua'),
        '260' => Array('ID' => '260', 'COD_A' => 'NL', 'COD_B' => 'NLD', 'DESCRIPCION' => 'Países Bajos'),
        '261' => Array('ID' => '261', 'COD_A' => 'NO', 'COD_B' => 'NOR', 'DESCRIPCION' => 'Noruega'),
        '262' => Array('ID' => '262', 'COD_A' => 'NP', 'COD_B' => 'NPL', 'DESCRIPCION' => 'Nepal'),
        '263' => Array('ID' => '263', 'COD_A' => 'NR', 'COD_B' => 'NRU', 'DESCRIPCION' => 'Nauru'),
        '264' => Array('ID' => '264', 'COD_A' => 'NU', 'COD_B' => 'NIU', 'DESCRIPCION' => 'Niue'),
        '265' => Array('ID' => '265', 'COD_A' => 'NZ', 'COD_B' => 'NZL', 'DESCRIPCION' => 'Nueva Zelanda'),
        '266' => Array('ID' => '266', 'COD_A' => 'OM', 'COD_B' => 'OMN', 'DESCRIPCION' => 'Omán'),
        '267' => Array('ID' => '267', 'COD_A' => 'PA', 'COD_B' => 'PAN', 'DESCRIPCION' => 'Panamá'),
        '268' => Array('ID' => '268', 'COD_A' => 'PE', 'COD_B' => 'PER', 'DESCRIPCION' => 'Perú'),
        '269' => Array('ID' => '269', 'COD_A' => 'PF', 'COD_B' => 'PYF', 'DESCRIPCION' => 'Polinesia Francesa'),
        '270' => Array('ID' => '270', 'COD_A' => 'PG', 'COD_B' => 'PNG', 'DESCRIPCION' => 'Papúa Nueva Guinea'),
        '271' => Array('ID' => '271', 'COD_A' => 'PH', 'COD_B' => 'PHL', 'DESCRIPCION' => 'Filipinas'),
        '272' => Array('ID' => '272', 'COD_A' => 'PK', 'COD_B' => 'PAK', 'DESCRIPCION' => 'Pakistán'),
        '273' => Array('ID' => '273', 'COD_A' => 'PL', 'COD_B' => 'POL', 'DESCRIPCION' => 'Polonia'),
        '274' => Array('ID' => '274', 'COD_A' => 'PM', 'COD_B' => 'SPM', 'DESCRIPCION' => 'San Pedro y Miquelón'),
        '275' => Array('ID' => '275', 'COD_A' => 'PN', 'COD_B' => 'PCN', 'DESCRIPCION' => 'Pitcairn'),
        '276' => Array('ID' => '276', 'COD_A' => 'PR', 'COD_B' => 'PRI', 'DESCRIPCION' => 'Puerto Rico'),
        '277' => Array('ID' => '277', 'COD_A' => 'PS', 'COD_B' => 'PSE', 'DESCRIPCION' => 'Palestina'),
        '278' => Array('ID' => '278', 'COD_A' => 'PT', 'COD_B' => 'PRT', 'DESCRIPCION' => 'Portugal'),
        '279' => Array('ID' => '279', 'COD_A' => 'PW', 'COD_B' => 'PLW', 'DESCRIPCION' => 'Palaos'),
        '280' => Array('ID' => '280', 'COD_A' => 'PY', 'COD_B' => 'PRY', 'DESCRIPCION' => 'Paraguay'),
        '281' => Array('ID' => '281', 'COD_A' => 'QA', 'COD_B' => 'QAT', 'DESCRIPCION' => 'Catar'),
        '282' => Array('ID' => '282', 'COD_A' => 'RE', 'COD_B' => 'REU', 'DESCRIPCION' => 'Reunión'),
        '283' => Array('ID' => '283', 'COD_A' => 'RO', 'COD_B' => 'ROU', 'DESCRIPCION' => 'Rumania'),
        '149' => Array('ID' => '149', 'COD_A' => 'RS', 'COD_B' => 'SRB', 'DESCRIPCION' => 'Serbia'),
        '284' => Array('ID' => '284', 'COD_A' => 'RU', 'COD_B' => 'RUS', 'DESCRIPCION' => 'Rusia'),
        '285' => Array('ID' => '285', 'COD_A' => 'RW', 'COD_B' => 'RWA', 'DESCRIPCION' => 'Ruanda'),
        '286' => Array('ID' => '286', 'COD_A' => 'SA', 'COD_B' => 'SAU', 'DESCRIPCION' => 'Arabia Saudita'),
        '287' => Array('ID' => '287', 'COD_A' => 'SB', 'COD_B' => 'SLB', 'DESCRIPCION' => 'Islas Salomón'),
        '288' => Array('ID' => '288', 'COD_A' => 'SC', 'COD_B' => 'SYC', 'DESCRIPCION' => 'Seychelles'),
        '289' => Array('ID' => '289', 'COD_A' => 'SD', 'COD_B' => 'SDN', 'DESCRIPCION' => 'Sudán'),
        '290' => Array('ID' => '290', 'COD_A' => 'SE', 'COD_B' => 'SWE', 'DESCRIPCION' => 'Suecia'),
        '291' => Array('ID' => '291', 'COD_A' => 'SG', 'COD_B' => 'SGP', 'DESCRIPCION' => 'Singapur'),
        '292' => Array('ID' => '292', 'COD_A' => 'SH', 'COD_B' => 'SHN', 'DESCRIPCION' => 'Santa Elena, Ascensión y Tristán de Acuña'),
        '293' => Array('ID' => '293', 'COD_A' => 'SI', 'COD_B' => 'SVN', 'DESCRIPCION' => 'Eslovenia'),
        '294' => Array('ID' => '294', 'COD_A' => 'SJ', 'COD_B' => 'SJM', 'DESCRIPCION' => 'Svalbard y Jan Mayen'),
        '295' => Array('ID' => '295', 'COD_A' => 'SK', 'COD_B' => 'SVK', 'DESCRIPCION' => 'Eslovaquia'),
        '296' => Array('ID' => '296', 'COD_A' => 'SL', 'COD_B' => 'SLE', 'DESCRIPCION' => 'Sierra Leona'),
        '297' => Array('ID' => '297', 'COD_A' => 'SM', 'COD_B' => 'SMR', 'DESCRIPCION' => 'San Marino'),
        '298' => Array('ID' => '298', 'COD_A' => 'SN', 'COD_B' => 'SEN', 'DESCRIPCION' => 'Senegal'),
        '299' => Array('ID' => '299', 'COD_A' => 'SO', 'COD_B' => 'SOM', 'DESCRIPCION' => 'Somalia'),
        '300' => Array('ID' => '300', 'COD_A' => 'SR', 'COD_B' => 'SUR', 'DESCRIPCION' => 'Surinam'),
        '344' => Array('ID' => '344', 'COD_A' => 'SS', 'COD_B' => 'SSD', 'DESCRIPCION' => 'Sudán del Sur'),
        '301' => Array('ID' => '301', 'COD_A' => 'ST', 'COD_B' => 'STP', 'DESCRIPCION' => 'Santo Tomé y Príncipe'),
        '302' => Array('ID' => '302', 'COD_A' => 'SV', 'COD_B' => 'SLV', 'DESCRIPCION' => 'El Salvador'),
        '303' => Array('ID' => '303', 'COD_A' => 'SY', 'COD_B' => 'SYR', 'DESCRIPCION' => 'Siria'),
        '304' => Array('ID' => '304', 'COD_A' => 'SZ', 'COD_B' => 'SWZ', 'DESCRIPCION' => 'Suazilandia'),
        '305' => Array('ID' => '305', 'COD_A' => 'TC', 'COD_B' => 'TCA', 'DESCRIPCION' => 'Islas Turcas y Caicos'),
        '306' => Array('ID' => '306', 'COD_A' => 'TD', 'COD_B' => 'TCD', 'DESCRIPCION' => 'Chad'),
        '308' => Array('ID' => '308', 'COD_A' => 'TG', 'COD_B' => 'TGO', 'DESCRIPCION' => 'Togo'),
        '309' => Array('ID' => '309', 'COD_A' => 'TH', 'COD_B' => 'THA', 'DESCRIPCION' => 'Tailandia'),
        '310' => Array('ID' => '310', 'COD_A' => 'TJ', 'COD_B' => 'TJK', 'DESCRIPCION' => 'Tayikistán'),
        '311' => Array('ID' => '311', 'COD_A' => 'TK', 'COD_B' => 'TKL', 'DESCRIPCION' => 'Tokelau'),
        '312' => Array('ID' => '312', 'COD_A' => 'TL', 'COD_B' => 'TLS', 'DESCRIPCION' => 'Timor Oriental'),
        '313' => Array('ID' => '313', 'COD_A' => 'TM', 'COD_B' => 'TKM', 'DESCRIPCION' => 'Turkmenistán'),
        '314' => Array('ID' => '314', 'COD_A' => 'TN', 'COD_B' => 'TUN', 'DESCRIPCION' => 'Túnez'),
        '315' => Array('ID' => '315', 'COD_A' => 'TO', 'COD_B' => 'TON', 'DESCRIPCION' => 'Tonga'),
        '316' => Array('ID' => '316', 'COD_A' => 'TR', 'COD_B' => 'TUR', 'DESCRIPCION' => 'Turquía'),
        '317' => Array('ID' => '317', 'COD_A' => 'TT', 'COD_B' => 'TTO', 'DESCRIPCION' => 'Trinidad y Tobago'),
        '318' => Array('ID' => '318', 'COD_A' => 'TV', 'COD_B' => 'TUV', 'DESCRIPCION' => 'Tuvalu'),
        '319' => Array('ID' => '319', 'COD_A' => 'TW', 'COD_B' => 'TWN', 'DESCRIPCION' => 'Taiwán'),
        '320' => Array('ID' => '320', 'COD_A' => 'TZ', 'COD_B' => 'TZA', 'DESCRIPCION' => 'Tanzania'),
        '321' => Array('ID' => '321', 'COD_A' => 'UA', 'COD_B' => 'UKR', 'DESCRIPCION' => 'Ucrania'),
        '322' => Array('ID' => '322', 'COD_A' => 'UG', 'COD_B' => 'UGA', 'DESCRIPCION' => 'Uganda'),
        '323' => Array('ID' => '323', 'COD_A' => 'UM', 'COD_B' => 'UMI', 'DESCRIPCION' => 'Islas Ultramarinas Menores de Estados Unidos'),
        '324' => Array('ID' => '324', 'COD_A' => 'US', 'COD_B' => 'USA', 'DESCRIPCION' => 'Estados Unidos'),
        '325' => Array('ID' => '325', 'COD_A' => 'UY', 'COD_B' => 'URY', 'DESCRIPCION' => 'Uruguay'),
        '326' => Array('ID' => '326', 'COD_A' => 'UZ', 'COD_B' => 'UZB', 'DESCRIPCION' => 'Uzbekistán'),
        '327' => Array('ID' => '327', 'COD_A' => 'VA', 'COD_B' => 'VAT', 'DESCRIPCION' => 'Vaticano'),
        '328' => Array('ID' => '328', 'COD_A' => 'VC', 'COD_B' => 'VCT', 'DESCRIPCION' => 'San Vicente y las Granadinas'),
        '329' => Array('ID' => '329', 'COD_A' => 'VE', 'COD_B' => 'VEN', 'DESCRIPCION' => 'Venezuela'),
        '330' => Array('ID' => '330', 'COD_A' => 'VG', 'COD_B' => 'VGB', 'DESCRIPCION' => 'Islas Vírgenes Británicas'),
        '331' => Array('ID' => '331', 'COD_A' => 'VI', 'COD_B' => 'VIR', 'DESCRIPCION' => 'Islas Vírgenes de los Estados Unidos'),
        '332' => Array('ID' => '332', 'COD_A' => 'VN', 'COD_B' => 'VNM', 'DESCRIPCION' => 'Vietnam'),
        '333' => Array('ID' => '333', 'COD_A' => 'VU', 'COD_B' => 'VUT', 'DESCRIPCION' => 'Vanuatu'),
        '334' => Array('ID' => '334', 'COD_A' => 'WF', 'COD_B' => 'WLF', 'DESCRIPCION' => 'Wallis y Futuna'),
        '335' => Array('ID' => '335', 'COD_A' => 'WS', 'COD_B' => 'WSM', 'DESCRIPCION' => 'Samoa'),
        '336' => Array('ID' => '336', 'COD_A' => 'YE', 'COD_B' => 'YEM', 'DESCRIPCION' => 'Yemen'),
        '337' => Array('ID' => '337', 'COD_A' => 'YT', 'COD_B' => 'MYT', 'DESCRIPCION' => 'Mayotte'),
        '338' => Array('ID' => '338', 'COD_A' => 'ZA', 'COD_B' => 'ZAF', 'DESCRIPCION' => 'Sudáfrica'),
        '339' => Array('ID' => '339', 'COD_A' => 'ZM', 'COD_B' => 'ZMB', 'DESCRIPCION' => 'Zambia'),
        '340' => Array('ID' => '340', 'COD_A' => 'ZW', 'COD_B' => 'ZWE', 'DESCRIPCION' => 'Zimbabue'),
        '107' => Array('ID' => '107', 'COD_A' => 'KK', 'COD_B' => 'KK ', 'DESCRIPCION' => 'Antillas Neerlandesas'),
        '500' => Array('ID' => '500', 'COD_A' => 'AA', 'COD_B' => 'AA ', 'DESCRIPCION' => 'ex URSS', 'I'),
        '501' => Array('ID' => '501', 'COD_A' => 'BB', 'COD_B' => 'BB ', 'DESCRIPCION' => 'ex Yugoslavia', 'I'),
        '913' => Array('ID' => '913', 'COD_A' => 'CC', 'COD_B' => 'CC ', 'DESCRIPCION' => 'Kosovo bajo mandato de la UNMIK', 'I'),
        '920' => Array('ID' => '920', 'COD_A' => 'DD', 'COD_B' => 'DD ', 'DESCRIPCION' => 'TPIY (Tribunal Penal Internacional para la ex Yugoslavia)', 'I'),
        '921' => Array('ID' => '921', 'COD_A' => 'EE', 'COD_B' => 'EE ', 'DESCRIPCION' => 'TPIR (Tribunal Penal Internacional para Ruanda)', 'I'),
        '922' => Array('ID' => '922', 'COD_A' => 'FF', 'COD_B' => 'FF ', 'DESCRIPCION' => 'TESL (Tribunal Especial para Sierra Leona)', 'I'),
        '923' => Array('ID' => '923', 'COD_A' => 'GG', 'COD_B' => 'GG ', 'DESCRIPCION' => 'CPI (Corte Penal Internacional)', 'I'),
        '924' => Array('ID' => '924', 'COD_A' => 'HH', 'COD_B' => 'HH ', 'DESCRIPCION' => 'TEL (Tribunal Especial para el Líbano)', 'I'),
        '997' => Array('ID' => '997', 'COD_A' => 'II', 'COD_B' => 'II ', 'DESCRIPCION' => 'Formación OIPC', 'I'),
        '999' => Array('ID' => '999', 'COD_A' => 'JJ', 'COD_B' => 'JJ ', 'DESCRIPCION' => 'OIPC', 'I'),
        '001' => Array('ID' => '001', 'COD_A' => 'SX', 'COD_B' => 'SXM', 'DESCRIPCION' => 'Sint Maarten', 'A'),
        '002' => Array('ID' => '002', 'COD_A' => 'IM', 'COD_B' => 'IMN', 'DESCRIPCION' => 'Isla de Man', 'A'),
        '003' => Array('ID' => '003', 'COD_A' => 'BL', 'COD_B' => 'BLM', 'DESCRIPCION' => 'San Bartolomé', 'A'),
        '004' => Array('ID' => '004', 'COD_A' => 'BQ', 'COD_B' => 'BES', 'DESCRIPCION' => 'Bonaire, San Eustaquio y Saba', 'A'),
        '005' => Array('ID' => '005', 'COD_A' => 'GG', 'COD_B' => 'GGY', 'DESCRIPCION' => 'Guernsey', 'A'),
        '006' => Array('ID' => '006', 'COD_A' => 'JE', 'COD_B' => 'JEY', 'DESCRIPCION' => 'Jersey', 'A')
    );
    
    private $Documentos = Array(
        '1'    => Array ( "ID" => '1' , "COD" =>  ""   , "DESCRIPCION" => "Sin especificar" ),
        '2'    => Array ( "ID" => '2' , "COD" =>  "ALP", "DESCRIPCION" => "Aliens Travel Document" ),
        '3'    => Array ( "ID" => '3' , "COD" =>  "ARN", "DESCRIPCION" => "Alien Registration Number" ),
        '4'    => Array ( "ID" => '4' , "COD" =>  "BTC", "DESCRIPCION" => "Birth Certificate" ),
        '5'    => Array ( "ID" => '5' , "COD" =>  "BUP", "DESCRIPCION" => "Business Passport" ),
        '6'    => Array ( "ID" => '6' , "COD" =>  "CLP", "DESCRIPCION" => "Collective Passport" ),
        '7'    => Array ( "ID" => '7' , "COD" =>  "CMC", "DESCRIPCION" => "Crew Member Certificate" ),
        '8'    => Array ( "ID" => '8' , "COD" =>  "COI", "DESCRIPCION" => "Certificate of identity" ),
        '9'    => Array ( "ID" => '9' , "COD" =>  "COP", "DESCRIPCION" => "Consular Passport" ),
        '10'   => Array ( "ID" => '10', "COD" =>  "CTD", "DESCRIPCION" => "Collective Transit Visa" ),
        '11'   => Array ( "ID" => '11', "COD" =>  "CVS", "DESCRIPCION" => "Collective Visa" ),
        '12'   => Array ( "ID" => '12', "COD" =>  "CZP", "DESCRIPCION" => "Citizen s Passport" ),
        '13'   => Array ( "ID" => '13', "COD" =>  "DCP", "DESCRIPCION" => "Diplomatic Courier Passport" ),
        '14'   => Array ( "ID" => '14', "COD" =>  "DIP", "DESCRIPCION" => "Diplomatic Passport" ),
        '15'   => Array ( "ID" => '15', "COD" =>  "DIV", "DESCRIPCION" => "Document of identity for Visa" ),
        '16'   => Array ( "ID" => '16', "COD" =>  "DRV", "DESCRIPCION" => "Driving licence" ),
        '17'   => Array ( "ID" => '17', "COD" =>  "EAP", "DESCRIPCION" => "Emergency Alien s Passport" ),
        '18'   => Array ( "ID" => '18', "COD" =>  "ECC", "DESCRIPCION" => "Vehicle EARPCCO Clearance Certificate" ),
        '19'   => Array ( "ID" => '19', "COD" =>  "EMC", "DESCRIPCION" => "Emergency Certificate" ),
        '20'   => Array ( "ID" => '20', "COD" =>  "EMD", "DESCRIPCION" => "Emergency Document" ),
        '21'   => Array ( "ID" => '21', "COD" =>  "EMP", "DESCRIPCION" => "Emergency Passport" ),
        '22'   => Array ( "ID" => '22', "COD" =>  "GLP", "DESCRIPCION" => "Local Passport-Travel Document" ),
        '23'   => Array ( "ID" => '23', "COD" =>  "HUP", "DESCRIPCION" => "Hunting permit" ),
        '24'   => Array ( "ID" => '24', "COD" =>  "I"  , "DESCRIPCION" => "All Identity document" ),
        '25'   => Array ( "ID" => '25', "COD" =>  "IDC", "DESCRIPCION" => "Identity card" ),
        '26'   => Array ( "ID" => '26', "COD" =>  "LAP", "DESCRIPCION" => "Special Entry" ),
        '27'   => Array ( "ID" => '27', "COD" =>  "MDP", "DESCRIPCION" => "Ministerial Service Passport" ),
        '28'   => Array ( "ID" => '28', "COD" =>  "MIP", "DESCRIPCION" => "Military Passport" ),
        '29'   => Array ( "ID" => '29', "COD" =>  "MPP", "DESCRIPCION" => "Member of Parliement Passport" ),
        '30'   => Array ( "ID" => '30', "COD" =>  "MSP", "DESCRIPCION" => "Ministerial Passport" ),
        '31'   => Array ( "ID" => '31', "COD" =>  "NAP", "DESCRIPCION" => "National Passport" ),
        '32'   => Array ( "ID" => '32', "COD" =>  "NID", "DESCRIPCION" => "National IDentification Number" ),
        '33'   => Array ( "ID" => '33', "COD" =>  "OCP", "DESCRIPCION" => "Official Collective Passport" ),
        '34'   => Array ( "ID" => '34', "COD" =>  "OFP", "DESCRIPCION" => "Official Passport" ),
        '35'   => Array ( "ID" => '35', "COD" =>  "OT1", "DESCRIPCION" => "Other 1" ),
        '36'   => Array ( "ID" => '36', "COD" =>  "OT2", "DESCRIPCION" => "Other 2" ),
        '37'   => Array ( "ID" => '37', "COD" =>  "OT3", "DESCRIPCION" => "Other 3" ),
        '38'   => Array ( "ID" => '38', "COD" =>  "OTH", "DESCRIPCION" => "Other" ),
        '39'   => Array ( "ID" => '39', "COD" =>  "OTX", "DESCRIPCION" => "Other x" ),
        '40'   => Array ( "ID" => '40', "COD" =>  "OTx", "DESCRIPCION" => "Other x" ),
        '41'   => Array ( "ID" => '41', "COD" =>  "P"  , "DESCRIPCION" => "All passport types" ),
        '42'   => Array ( "ID" => '42', "COD" =>  "PAS", "DESCRIPCION" => "Passport" ),
        '43'   => Array ( "ID" => '43', "COD" =>  "PRP", "DESCRIPCION" => "Permanent Residence Permit" ),
        '44'   => Array ( "ID" => '44', "COD" =>  "PTR", "DESCRIPCION" => "Permit to re-enter" ),
        '45'   => Array ( "ID" => '45', "COD" =>  "RES", "DESCRIPCION" => "Residence permit" ),
        '46'   => Array ( "ID" => '46', "COD" =>  "RFP", "DESCRIPCION" => "Travel Document for Refugees" ),
        '47'   => Array ( "ID" => '47', "COD" =>  "SCC", "DESCRIPCION" => "Vehicle SARPCCO Clearance Certificate" ),
        '48'   => Array ( "ID" => '48', "COD" =>  "SEP", "DESCRIPCION" => "Service Passport" ),
        '49'   => Array ( "ID" => '49', "COD" =>  "SIB", "DESCRIPCION" => "Seaman s Book" ),
        '50'   => Array ( "ID" => '50', "COD" =>  "SOC", "DESCRIPCION" => "Social Security Number" ),
        '51'   => Array ( "ID" => '51', "COD" =>  "SPE", "DESCRIPCION" => "Special Document" ),
        '52'   => Array ( "ID" => '52', "COD" =>  "SPP", "DESCRIPCION" => "Special Passport" ),
        '53'   => Array ( "ID" => '53', "COD" =>  "STP", "DESCRIPCION" => "Student Passport" ),
        '54'   => Array ( "ID" => '54', "COD" =>  "TCA", "DESCRIPCION" => "Document for citizens Abroad" ),
        '55'   => Array ( "ID" => '55', "COD" =>  "TDS", "DESCRIPCION" => "Transit Visa" ),
        '56'   => Array ( "ID" => '56', "COD" =>  "TLP", "DESCRIPCION" => "Travel Document in Lieu of National Passport" ),
        '57'   => Array ( "ID" => '57', "COD" =>  "TRC", "DESCRIPCION" => "Travel Certificate" ),
        '58'   => Array ( "ID" => '58', "COD" =>  "TRP", "DESCRIPCION" => "Temporary Residence Permit" ),
        '59'   => Array ( "ID" => '59', "COD" =>  "TTD", "DESCRIPCION" => "Temporary Travel Document" ),
        '60'   => Array ( "ID" => '60', "COD" =>  "V"  , "DESCRIPCION" => "All Visa types" ),
        '61'   => Array ( "ID" => '61', "COD" =>  "VOC", "DESCRIPCION" => "Vehicle Owner Certificate" ),
        '62'   => Array ( "ID" => '62', "COD" =>  "VRB", "DESCRIPCION" => "Navigation License" ),
        '63'   => Array ( "ID" => '63', "COD" =>  "VRD", "DESCRIPCION" => "Vehicle Registration Document" ),
        '64'   => Array ( "ID" => '64', "COD" =>  "VSA", "DESCRIPCION" => "Visa" ),
        '65'   => Array ( "ID" => '65', "COD" =>  "YOP", "DESCRIPCION" => "Youth-Child Passport" )
    );
    

    private $Offence_Code = Array(
        "FGM" => "PASAPORTE EXTRAVIADO",
        "FIM" => "CÉDULA DE IDENTIDAD EXTRAVIADA",
        "FKM" => "DOCUMENTO ADMINISTRATIVO EXTRAVIADO",
        "I" => "DELITOS CONTRA LAS PERSONAS",
        "IC" => "DELITOS CONTRA LOS NIÑOS",
        "ICM" => "DELITOS CONTRA LOS NIÑOS",
        "IF" => "PRÓFUGOS",
        "IFA" => "AUXILIO A UN DELINCUENTE",
        "IFE" => "EVADIDO",
        "IFL" => "EN LIBERTAD",
        "IH" => "GENOCIDIO, CRÍMENES DE GUERRA Y CRÍMENES CONTRA LA HUMANIDAD",
        "IHG" => "GENOCIDIO",
        "IHH" => "CRÍMENES CONTRA LA HUMANIDAD   ",
        "IHW" => "CRIMEN DE GUERRA",
        "II" => "TRÁFICO Y TRATA DE SERES HUMANOS E INMIGRACIÓN CLANDESTINA",
        "III" => "INMIGRACIÓN CLANDESTINA",
        "IIO" => "ORGANIZADOR DE OPERACIONES DE INMIGRACIÓN CLANDESTINA",
        "IIP" => "ORGANIZADOR DE OPERACIONES DE TRATA DE SERES HUMANOS",
        "IIT" => "TRATA DE SERES HUMANOS",
        "IK" => "SECUESTRO",
        "IKA" => "DELITO CONTRA LA FAMILIA O SUSTRACCIÓN DE MENOR",
        "IKK" => "SECUESTRO O DETENCIÓN ILEGAL",
        "IKP" => "PIRATERÍA MARÍTIMA / ROBO EN ALTA MAR",
    "IL" => "DELITOS CONTRA LA VIDA Y LA SALUD",
    "ILA"=> "AGRESIÓN O MALOS TRATOS",
    "ILB"=> "TORTURA O ACTOS DE BARBARIE",
    "ILG"=> "PROFANACIÓN DE SEPULTURA",
    "ILM"=> "LESIONES CON RESULTADO DE MUERTE, HOMICIDIO O ASESINATO",
    "ILO"=> "ÓRGANOS (SE ESTUDIARÁ LA POSIBLE INCLUSIÓN DE LAS HORMONAS)",
    "ILT"=> "AMENAZAS",
    "IM"=> "PERSONAS DESAPARECIDAS",
    "IMM"=> "PERSONA DESAPARECIDA",
    "IO"=> "DELINCUENCIA ORGANIZADA Y DELINCUENCIA TRANSNACIONAL",
    "IOO"=> "ORGANIZACIÓN, ASOCIACIÓN O GRUPO DELICTIVO",
    "IR"=> "DELITO CONTRA LA SEGURIDAD DEL TRÁFICO",
    "IRO"=> "DELITO CONTRA LA SEGURIDAD DEL TRÁFICO",
    "IS"=> "DELITOS SEXUALES",
    "ISE"=> "EXPLOTACIÓN SEXUAL O PROSTITUCIÓN",
    "ISP"=> "PRODUCCIÓN O DISTRIBUCIÓN DE PORNOGRAFÍA",
    "ISR"=> "VIOLACIÓN",
    "ISS"=> "DELITOS SEXUALES",
    "IT"=> "TERRORISMO",
    "ITA"=> "APOYO LOGÍSTICO AL TERRORISMO",
    "ITB"=> "TERRORISMO BIOLÓGICO",
    "ITC"=> "TERRORISMO QUÍMICO",
    "ITF"=> "FINANCIACIÓN DEL TERRORISMO",
    "ITM"=> "MIEMBRO DE GRUPO TERRORISTA",
    "ITR"=> "TERRORISMO RADIOLÓGICO O NUCLEAR",
    "ITT"=> "DELITO RELACIONADO CON EL TERRORISMO",
    "ITZ"=> "ACTOS TERRORISTAS",
    "IU"=> "CADÁVERES POR IDENTIFICAR",
    "IUC"=> "CADÁVER POR IDENTIFICAR",
    "NLC"=> "NOT LISTED CRIME",
    "O"=> "",
    "OLDFGM"=> "",
    "OLDFIM"=> "",
    "OLDFKM"=> "",
    "OLDVCC"=> "",
    "OLDZAZ"=> "",
    "OLDZCA"=> "",
    "OLDZCE"=> "",
    "OLDZCI"=> "",
    "OLDZCN"=> "",
    "OLDZCS"=> "",
    "OLDZED"=> "",
    "P"=> "DELITOS CONTRA LA PROPIEDAD, LAS EMPRESAS Y EL ESTADO",
    "PA"=> "DELITOS RELACIONADOS CON LAS OBRAS DE ARTE",
    "PAA"=> "OBRAS DE ARTE",
    "PC"=> "FALSIFICACIONES E IMITACIONES",
    "PCF"=> "FALSIFICACIÓN",
    "PCM"=> "FALSIFICACIÓN DE MONEDA",
    "PCO"=> "CRIMEN FARMACOLÓGICO",
    "PCP"=> "DELITO CONTRA LA PROPIEDAD INTELECTUAL E INDUSTRIAL",
    "PCT"=> "FALSIFICACIÓN DE DOCUMENTOS DE VIAJE",
    "PD"=> "DELITOS RELACIONADOS CON LAS DROGAS",
    "PDA"=> "AGENTES DOPANTES Y ANABOLIZANTES",
    "PDC"=> "CANNABIS",
    "PDD"=> "DROGAS",
    "PDH"=> "HEROÍNA, MORFINA Y OPIO",
    "PDO"=> "COCAÍNA",
    "PDP"=> "SUSTANCIAS SICOTRÓPICAS",
    "PE"=> "DELINCUENCIA CONTRA EL MEDIO AMBIENTE",
    "PEB"=> "AGENTES BACTERIOLÓGICOS, BIOLÓGICOS Y NUCLEARES",
    "PEC"=> "SUSTANCIAS QUÍMICAS",
    "PEE"=> "DELITO CONTRA EL MEDIO AMBIENTE",
    "PEW"=> "DELITO CONTRA LA FAUNA Y LA FLORA SILVESTRES",
    "PF"=> "DEFRAUDACIONES",
    "PFB"=> "FRAUDE BANCARIO O FINANCIERO",
    "PFC"=> "COHECHO O CORRUPCIÓN",
    "PFF"=> "ESTAFA",
    "PFG"=> "FRAUDE CONTRA LA ADMINISTRACIÓN PÚBLICA",
    "PFM"=> "FRAUDE COMERCIAL",
    "PFV"=> "INFRACCIÓN DE LA REGLAMENTACIÓN SOBRE DIVISAS",
    "PH"=> "PIRATERÍA",
    "PHH"=> "SECUESTRO DE MEDIO DE TRANSPORTE (AERONAVE, EMBARCACIÓN, AUTOBÚS, AUTOMÓVIL, ETC.)",
    "PI"=> "DELINCUENCIA INFORMÁTICA",
    "PIH"=> "DELITOS DE ALTA TECNOLOGÍA",
    "PM"=> "BLANQUEO DE CAPITALES",
    "PMM"=> "BLANQUEO DE CAPITALES",
    "PT"=> "HURTO O ROBO",
    "PTA"=> "HURTO CON AGRAVANTES",
    "PTB"=> "",
    "PTE"=> "",
    "PTR"=> "RECEPTACIÓN DE BIENES ROBADOS",
    "PTS"=> "HURTO",
    "PTW"=> "ROBO A MANO ARMADA",
    "PTY"=> "",
    "PV"=> "GAMBERRISMO, VANDALISMO Y DAÑOS",
    "PVV"=> "VANDALISMO, DAÑOS, SAQUEO Y GAMBERRISMO",
    "PW"=> "DELITOS PERPETRADOS MEDIANTE EL USO DE ARMAS Y EXPLOSIVOS",
    "PWA"=> "INCENDIO PROVOCADO",
    "PWC"=> "MUNICIONES, COMPONENTES, ARMAS DE FUEGO, ARMAS O EXPLOSIVOS",
    "PWE"=> "UTILIZACIÓN DE ARTEFACTO EXPLOSIVO (ATENTADO CON BOMBA)",
    "VCC"=> "ASOCIACIÓN ILÍCITA",
    "ZAZ"=> "RELEVANCIA INTERNACIONAL (OTROS TIPOS)",
    "ZCA"=> "TENTATIVA",
    "ZCE"=> "FALSO TESTIMONIO",
    "ZCI"=> "INCITACIÓN",
    "ZCN"=> "ASOCIACIÓN ILÍCITA",
    "ZCS"=> "PRESUNCIÓN",
    "ZED"=> "CONDUCCIÓN BAJO LA INFLUENCIA DEL ALCOHOL/DE DROGAS");
    
    
    private $coloresId = Array(
        'ALU' => 'Aluminio',
        'BEI' => 'Beige',
        'BLA' => 'Negro',
        'BLU' => 'Azul',
        'BRO' => 'Marrón',
        'BRZ' => 'Bronce',
        'CLA' => 'Vino',
        'COP' => 'Cobre',
        'CRM' => 'Crema',
        'GOL' => 'Oro',
        'GRA' => 'Gris',
        'GRE' => 'Verde',
        'MAR' => 'Granate',
        'MTL' => 'Metálico',
        'NAV' => 'Azul Marino',
        'ORA' => 'Naranja',
        'PIN' => 'Rosa',
        'PUR' => 'Púrpura',
        'RED' => 'Rojo',
        'ROS' => 'Rosado',
        'RUS' => 'Óxido',
        'SIL' => 'Plata',
        'TAN' => 'Tostado',
        'TUR' => 'Turquesa',
        'WHI' => 'Blanco',
        'YEL' => 'Amarillo'
    );
    
    
    
    // Consulta de documento
    public function getPaises(){
        
        $respuesta = $this->array_sort( $this->Paises, "ID" );
        
        return $respuesta;
    }

    public function getPais($IdPais){
        
        $respuesta = $this->Paises[$IdPais];
        
        return $respuesta;
    }
    
    // Consulta de documento
    public function getDocumentos(){
        
        $respuesta = $this->array_sort( $this->Documentos,"COD");
        
        return $respuesta;
    }
    
    public function getDocumento($IdDocumento){
        
        $respuesta = $this->Documentos[$IdDocumento];
        
        return $respuesta;
    }
    
    public function getOffencesCode(){
        
        $respuesta = $this->Offence_Code;
        
        return $respuesta;
    }
    public function getOffenceCode($Offencecode){
        
        $respuesta = $this->$Offence_Code[$Offencecode];
        
        return $respuesta;
    }
    
    
    public function getColoresId()
    {
        return $this->coloresId;
    }
    
    
    private function array_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();
        
        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }
            
            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }
            
            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }
        
        return $new_array;
    }


    
}   //  class Diccionario
?>