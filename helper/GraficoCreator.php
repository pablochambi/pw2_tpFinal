<?php
// content="text/plain; charset=utf-8"
//require_once('./jpgraph/src/jpgraph.php');
//require_once('./jpgraph/src/jpgraph_line.php');

class GraficoCreator
{
    public function __construct()
    {
    }

    public function preguntasCreadasPorDia($data)
    {
        $cantidades = array();
        $fechas = array();

        foreach ($data as $item) {
            $cantidades[] = $item['cantidad'];
            $fechas[] = $item['fecha'];
        }
        $grafico = $this->crearGrafico($fechas, $cantidades);

        $grafico->Stroke();

    }

    public function usuariosNuevosPorDia($data)
    {
        $cantidades = array();
        $fechas = array();

        foreach ($data as $item) {
            if (isset($item['cantidad']) && isset($item['fecha'])) {
                $cantidades[] = $item['cantidad'];
                $fechas[] = $item['fecha'];
            } else {
                echo "Error: Datos incompletos en el array de entrada.";
                return;
            }
        }

        if (empty($fechas) || empty($cantidades)) {
            echo "Error: Las listas de fechas o cantidades están vacías.";
            return;
        }

        $grafico = $this->crearGrafico($fechas, $cantidades);

        $grafico->Stroke();

    }

    public function partidasPorDia($data)
    {
        $cantidades = array();
        $fechas = array();

        foreach ($data as $item) {
            $cantidades[] = $item['cantidad'];
            $fechas[] = $item['fecha'];
        }
        $grafico = $this->crearGrafico($fechas, $cantidades);

        $grafico->Stroke();

    }


    public function usuariosPorSexo($data)
    {
        $cantidades = array();
        $sexos = array();

        foreach ($data as $item) {
            $cantidades[] = $item['cantidad'];
            $sexos[] = $item['sexo'];
        }
        $graph = $this->crearGrafico($sexos, $cantidades);

        $graph->Stroke();

    }

    public function usuariosPorGrupo($data)
    {
        $cantidades = array();
        $grupoEdad = array();

        foreach ($data as $item) {
            $cantidades[] = $item['cantidad'];
            $grupoEdad[] = $item['grupo_edad'];
        }
        $graph = $this->crearGrafico($grupoEdad, $cantidades);
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
        $graph = new Graph(400, 400, 'auto');
        $graph->SetScale("textlin");

        $theme_class = new UniversalTheme;
        $graph->SetTheme($theme_class);

        $graph->Set90AndMargin(150, 40, 40, 40);
        $graph->img->SetAngle(90);

// set major and minor tick positions manually
        $graph->SetBox(false);

//$graph->ygrid->SetColor('gray');
        $graph->ygrid->Show(false);
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($datosPaises);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

// For background to be gradient, setfill is needed first.
        $graph->SetBackgroundGradient('#00CED1', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

// Create the bar plots
        $b1plot = new BarPlot($datay);

// ...and add it to the graPH
        $graph->Add($b1plot);

        $b1plot->SetWeight(0);
        $b1plot->SetFillGradient("#808000", "#90EE90", GRAD_HOR);
        $b1plot->SetWidth(17);

// Display the graph
        $graph->Stroke();

    }

    public function porcentajeUsuarioCorrectas($dato)
    {
        $datay = array();
        $datosUsuarios = array();

        foreach ($dato as $item) {
            $datay[] = $item['porcentaje'];
            $datosUsuarios[] = $item['username'];
        }

        // Create the graph. These two calls are always required
        $graph = new Graph(400, 400, 'auto');
        $graph->SetScale("textlin");

        $theme_class = new UniversalTheme;
        $graph->SetTheme($theme_class);

        $graph->Set90AndMargin(150, 40, 40, 40);
        $graph->img->SetAngle(90);

// set major and minor tick positions manually
        $graph->SetBox(false);

//$graph->ygrid->SetColor('gray');
        $graph->ygrid->Show(false);
        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($datosUsuarios);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

// For background to be gradient, setfill is needed first.
        $graph->SetBackgroundGradient('#00CED1', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

// Create the bar plots
        $b1plot = new BarPlot($datay);

// ...and add it to the graPH
        $graph->Add($b1plot);

        $b1plot->SetWeight(0);
        $b1plot->SetFillGradient("#808000", "#90EE90", GRAD_HOR);
        $b1plot->SetWidth(17);

// Display the graph
        $graph->Stroke();

    }

    public function crearGrafico($datosEjeX, $datosEjeY): Graph
    {
        //$datosEjeX = fechas, grupoEdad, sexos
        //$datosEjeY = cantidades
        if (count($datosEjeX) !== count($datosEjeY)) {
            echo "Error: Las listas de fechas y cantidades no tienen el mismo tamaño.";
            exit();
        }

// Create the graph. These two calls are always required
        $graph = new Graph(500, 300, 'auto');
        $graph->SetScale("textlin");

        $theme_class = new UniversalTheme;
        $graph->SetTheme($theme_class);

        //$graph->yaxis->SetTickPositions(array(0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22));//, array(5,15,25,35,45)
        $graph->SetBox(false);

        $graph->ygrid->SetFill(false);
        $graph->xaxis->SetTickLabels($datosEjeX);
        $graph->yaxis->HideLine(false);
        $graph->yaxis->HideTicks(false, false);

// Create the bar plots
        $b1plot = new BarPlot($datosEjeY);

// Create the grouped bar plot
        $gbplot = new GroupBarPlot(array($b1plot));
// ...and add it to the graPH
        $graph->Add($gbplot);


        $b1plot->SetColor("white");
        $b1plot->SetFillColor("#ADD8E6");

        //$graph->title->Set("Cantidad de preguntas creadas");

        return $graph;
    }


}