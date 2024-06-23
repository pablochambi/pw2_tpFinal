<?php
// content="text/plain; charset=utf-8"
//require_once('./jpgraph/src/jpgraph.php');
//require_once('./jpgraph/src/jpgraph_line.php');

class GraficoCreator
{
    public function __construct()
    {
    }
    public function graficar($data)
    {
        $data1y=array($data[0]['cantidad'],$data[1]['cantidad'],$data[2]['cantidad'],
            $data[3]['cantidad'],$data[4]['cantidad'],$data[5]['cantidad'],$data[6]['cantidad']);

// Create the graph. These two calls are always required
        $graph = new Graph(500,300,'auto');
        $graph->SetScale("textlin");

        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);

        $graph->yaxis->SetTickPositions(array(0,2,4,6,8,10,12,14,16,18,20,22));//, array(5,15,25,35,45)
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array($data[0]['fecha'],$data[1]['fecha'],$data[2]['fecha'],
            $data[3]['fecha'],$data[4]['fecha'],$data[5]['fecha'],$data[6]['fecha']));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

// Create the bar plots
        $b1plot = new BarPlot($data1y);
        //$b2plot = new BarPlot($data2y);
        //$b3plot = new BarPlot($data3y);

// Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot));
// ...and add it to the graPH
        $graph->Add($gbplot);


        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#ADD8E6");


        //$graph->title->Set("Cantidad de preguntas creadas");

// Display the graph
        $graph->Stroke();
    }

    public function usuariosPorSexo($data)
    {

        $data1y = array($data[0]['cantidad'],$data[1]['cantidad'],$data[2]['cantidad']);

        // Create the graph. These two calls are always required
        $graph = new Graph(500,300,'auto');
        $graph->SetScale("textlin");

        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);

        $graph->yaxis->SetTickPositions(array(0,2,4,6,8,10,12,14,16,18,20,22));//, array(5,15,25,35,45)
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array($data[0]['sexo'],$data[1]['sexo'],$data[2]['sexo']));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        // Create the bar plots
        $b1plot = new BarPlot($data1y);
        // Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#ADD8E6");

        //$graph->title->Set("Cantidad de preguntas creadas");

        $graph->Stroke();
    }
    public function usuariosPorGrupo($data)
    {

        $data1y = array($data[0]['cantidad'],$data[1]['cantidad'],$data[2]['cantidad']);

        // Create the graph. These two calls are always required
        $graph = new Graph(500,300,'auto');
        $graph->SetScale("textlin");

        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);

        $graph->yaxis->SetTickPositions(array(0,2,4,6,8,10,12,14,16,18,20,22));//, array(5,15,25,35,45)
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels(array($data[0]['grupo_edad'].' de 18',$data[1]['grupo_edad'].' (18-59)',$data[2]['grupo_edad'].' (60+)'));
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

        // Create the bar plots
        $b1plot = new BarPlot($data1y);
        // Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot));
        // ...and add it to the graPH
        $graph->Add($gbplot);

        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#ADD8E6");

        //$graph->title->Set("Cantidad de preguntas creadas");

        $graph->Stroke();
    }

    public function usuariosPorPais($dato)
    {

        $datay = array();
        $datosPaises = array();

        foreach ($dato as $item) {
            $datay[] = $item['cant_usuarios'];
            $datosPaises[] = $item['pais'];
        }

// Create the graph. These two calls are always required
        $graph = new Graph(400,400,'auto');
        $graph->SetScale("textlin");

        $theme_class=new UniversalTheme;
        $graph->SetTheme($theme_class);

        $graph->Set90AndMargin(150,40,40,40);
        $graph->img->SetAngle(90);

// set major and minor tick positions manually
        $graph->SetBox(false);

//$graph->ygrid->SetColor('gray');
        $graph->ygrid->Show(false);
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($datosPaises);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false,false);

// For background to be gradient, setfill is needed first.
        $graph->SetBackgroundGradient('#00CED1', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

// Create the bar plots
        $b1plot = new BarPlot($datay);

// ...and add it to the graPH
        $graph->Add($b1plot);

        $b1plot->SetWeight(0);
        $b1plot->SetFillGradient("#808000","#90EE90",GRAD_HOR);
        $b1plot->SetWidth(17);

// Display the graph
        $graph->Stroke();
    }


}