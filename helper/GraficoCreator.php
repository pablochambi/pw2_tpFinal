<?php
// content="text/plain; charset=utf-8"
//require_once('./jpgraph/src/jpgraph.php');
//require_once('./jpgraph/src/jpgraph_line.php');

class GraficoCreator
{
    public function __construct()
    {
    }
    public function graficar()
    {
        $datay1 = array(20, 15, 23, 15);

        // Setup the graph
        $graph = new Graph(300, 250);
        $graph->SetScale("textlin");

        // Create the first line
        $p1 = new BarPlot($datay1);
        $graph->Add($p1);

        // Output line
        $graph->Stroke();
    }
}