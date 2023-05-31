<?php

// if (!isset($root)) $root = '../../../../';

// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Reader/Common/Creator/ReaderEntityFactory.php';
// include_once $root . 'inc/excel/spout-3.3.0/src/Spout/Autoloader/autoload.php';

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

/**Clase para la creacion de una tabla a partir de un fichero xls o csv */
class TablaModel
{

    private $filePath;

    /**Constructor con la ruta del fichero CSV/XLSX */
    public function __construct($filePath)
    {
        $this->setFilePath($filePath);
    }

    /**
     * Genera una tabla html a partir de un fichero .csv o .xlsx a través de un switch case 
     * la función se encarga de realizar el código necesario para que se genere correctamente
     */
    public function generarTablaFromFile()
    {
        $arrayFilas = [];
        $arrayLetras = [];
        $file = $this->getFilePath();

        // if ((new SplFileInfo($file))->getExtension() == 'csv') {
        //     $reader = ReaderEntityFactory::createCSVReader();
        //     $delimiter = $this->detectDelimiter($file);
        //     $reader->setFieldDelimiter($delimiter);
        // } else {
        //     $reader = ReaderEntityFactory::createXLSXReader();
        // }

        // $reader->open($file);

        // $reader = crearLectorFile($file);
        // $reader->open($file);

        // foreach ($reader->getSheetIterator() as $sheet) {
        //     foreach ($sheet->getRowIterator() as $row) {
        //         // do stuff with the row
        //         $cells = $row->getCells();
        //         if ($count == 0) {
        //             $arrayLetras = $this->letrasExcel(count($cells));
        //             array_push($arrayFilas, $cells);
        //             $count++;
        //         } else {
        //             array_push($arrayFilas, $cells);
        //         }
        //     }
        // }
        // $reader->close();

        $arrayFilas = leerFile($file);
        $arrayLetras = arrayLetrasColumnas(sizeof($arrayFilas[0]));
        array_unshift($arrayFilas, $arrayLetras);
        // $arrayFilas = arrayNumFilastabla($arrayFilas);

        $this->pintarTabla($arrayFilas);
    }


    /**Pinta la tabla en el html */
    private function pintarTabla($tabla)
    {
?>
        <html>

        <head>
            <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-grid.min.css" integrity="sha512-JQksK36WdRekVrvdxNyV3B0Q1huqbTkIQNbz1dlcFVgNynEMRl0F8OSqOGdVppLUDIvsOejhr/W5L3G/b3J+8w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-reboot.min.css" integrity="sha512-IS8Z2ZgFvTz/yLxE6H07ip/Ad+yAGswoD1VliOeC2T4WaPFNPC1TwmQ5zomGS+syaR2oO3aXJGKaHv21Dspx0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap-utilities.min.css" integrity="sha512-DEGBrwaCF4lkKzMKNwt8Qe/V54bmJctk7I1HyfINGAIugDvsdBeuWzAWZmXAmm49P6EBfl/OeUM01U3r7cW4jg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

        </head>

        <body>
            <table class="table table-dark table-striped">
                <?php
                $concat = '';
                $i = 0;
                foreach ($tabla as $fila) {

                    $concat .= '<tr>';
                    for ($i = 0; $i < count($fila); $i++) {
                        $concat .= '<td>' . $fila[$i] . '</td>';
                    }
                    $concat .= '</tr>';
                    $i++;
                }
                echo $concat;
                ?>
            </table>
        </body>

        </html>
<?php
    }

    //Getters & Setters

    /**Return filePath */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**Set filePath */
    public function setFilePath($file)
    {
        $this->filePath = $file;
    }
}
