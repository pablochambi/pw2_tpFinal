<?php
class AdministradorModel extends BaseModel
{
    protected $grafica;
    public function __construct($database,$grafica)
    {
        parent::__construct($database);
        $this->grafica = $grafica;
    }

    public function crearGrafico()
    {
        $this->grafica->graficar();
    }


}