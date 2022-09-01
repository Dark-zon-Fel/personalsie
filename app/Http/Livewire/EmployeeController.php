<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AreaTrabajo;
use App\Models\Employee;
use Livewire\withPagination;
use Livewire\withFileUploads;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Component
{
    use withPagination;
    use withFileUploads;

    //jdhdghd
    public $ci, $name, $lastname, $genero, $dateNac, $address, $phone, $dateAdmission, $areaid, $selected_id;
    public $pageTitle, $componentName, $search;
    public $details, $sumDetails, $countDetails, $saleId;
    private $pagination = 5;

    public $TiempoTranscurrido;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount(){
        $this->pageTitle = 'Listado';
        $this->componentName = 'Empleados';
        $this->areaid = 'Elegir';
        $this->genero = 'Seleccionar';
    }

    public function render()
    {
        Carbon::setLocale('es');
        $TiempoTranscurrido = setlocale(LC_TIME, 'es_ES.utf8');
        
        $fechaInicio = '$dateAdmission';
        $fechaActual = Carbon::now();

        $segundos = strtotime($fechaActual) - strtotime($fechaInicio);  // segundos
        $segRedondeados = floor($segundos);

        $minutos = $segRedondeados / 60;    // minutos
        $minRedondeados = floor($minutos);

        $horas = $minRedondeados / 60;  // horas
        $horasRedondeados = floor($horas);

        $dias = $horasRedondeados / 24;     // dias
        $diasRedondeados = floor($dias);    // para redondeo de un dia mas ceil()

        $meses = $diasRedondeados / 28;     // meses
        $mesesRedondeados = floor($meses);

        $años = $mesesRedondeados - 12;     // años
        $añosRedondeados = floor($años);

        if($añosRedondeados > 0){
            $TiempoTranscurrido = $añosRedondeados . " Años ". $mesesRedondeados . " Meses y ". $diasRedondeados . " Dias";
        }else{
            if($añosRedondeados < 1){
                $TiempoTranscurrido = $mesesRedondeados . " Meses y ". $diasRedondeados . " Dias";
            }else{
                $TiempoTranscurrido = $diasRedondeados . " Dias";
            }
        }

        if(strlen($this->search) > 0)
            $employ = Employee::join('area_trabajos as c', 'c.id', 'employees.area_trabajo_id') // se uno amabas tablas
            ->select('employees.*','c.name as area')
            ->where('employees.name', 'like', '%' . $this->search . '%')    // busquedas employees
            ->orWhere('employees.ci', 'like', '%' . $this->search . '%')    // busquedas
            ->orWhere('c.name', 'like', '%' . $this->search . '%')          // busqueda nombre de categoria
            ->orderBy('employees.name', 'asc')
            ->paginate($this->pagination);
        else
            $employ = Employee::join('area_trabajos as c', 'c.id', 'employees.area_trabajo_id')
            ->select('employees.*','c.name as area')
            ->orderBy('employees.name', 'asc')
            ->paginate($this->pagination);
        
        return view('livewire.employee.component', [
            'data' => $employ,    //se envia data
            'areas' => AreaTrabajo::orderBy('name', 'asc')->get(),
            'tiempos' => $TiempoTranscurrido
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    // Registro de empleado nuevo
    public function Store(){
        
        $rules = [
            'ci' => 'required|unique:employees',
            'name' => 'required',
            'lastname' => 'required',
            'genero' => 'required|not_in:Seleccionar',
            'dateNac' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:employees',
            'dateAdmission' => 'required',
            'areaid' => 'required|not_in:Elegir'
        ];
        $messages =  [
            'ci.required' => 'numero de cedula de identidad requerida',
            'ci.unique' => 'ya existe el numero de documento en el sistema',

            'name.required' => 'el nombre de empleado es requerida',

            'lastname.required' => 'los apellidos del empleado son requerida',
            
            'genero.required' => 'seleccione el genero del empleado',
            'genero.not_in' => 'selecciona genero',

            'dateNac.required' => 'la fecha de nacimiento es requerido',

            'address.required' => 'la direccion es requerida',

            'phone.required' => 'el numero de telefono es requerido',
            'phone.unique' => 'el numero de telefono ya existe en sistema',

            'dateAdmission.required' => 'la fecha de admision es requerido',

            'areaid.not_in' => 'elije un nombre de area diferente de elegir',
        ];

        $this->validate($rules, $messages);

        $employ = Employee::create([
            'ci' =>$this->ci, 
            'name'=>$this->name,
            'lastname'=>$this->lastname,
            'genero'=>$this->genero,
            'dateNac'=>$this->dateNac,
            'address'=>$this->address,
            'phone'=>$this->phone,
            'dateAdmission'=>$this->dateAdmission,
            'area_trabajo_id' => $this->areaid
        ]);

        $this->resetUI();
        $this->emit('employee-added', 'Empleado Registrado');
    }

    // editar informacion
    public function Edit(Employee $employee){

        $this->ci = $employee->ci;
        $this->name = $employee->name;
        $this->lastname = $employee->lastname;
        $this->genero = $employee->genero;
        $this->dateNac = $employee->dateNac;
        $this->address = $employee->address;
        $this->phone = $employee->phone;
        $this->dateAdmission = $employee->dateAdmission;
        $this->areaid = $employee->area_trabajo_id;
        $this->selected_id = $employee->id;

        $this->emit('modal-show', 'Show modal!');
    }

    // actulizar informacion
    public function Update(){
        $rules = [
            'ci' => "required|unique:employees,ci,{$this->selected_id}",
            'name' => 'required',
            'lastname' => 'required',
            'genero' => 'required|not_in:Seleccionar',
            'dateNac' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'dateAdmission' => 'required',
            'areaid' => 'required|not_in:Elegir'
        ];
        $messages =  [
            'ci.required' => 'numero de cedula de identidad requerida',
            'ci.unique' => 'ya existe el numero de documento en el sistema',

            'name.required' => 'el nombre de empleado es requerida',
            'lastname.required' => 'los apellidos del empleado son requerida',

            'genero.required' => 'el genero del empleado es requerido',
            'genero.not_in' => 'selcciona genero',

            'dateNac.required' => 'la fecha de nacimiento es requerido',

            'address.required' => 'la direccion es requerida',
            'phone.required' => 'el numero de telefono es requerido',
            'dateAdmission.required' => 'la fecha de admision es requerido',

            'areaid.not_in' => 'elije un nombre de area diferente de elegir',
        ];

        $this->validate($rules, $messages);

        $employee = Employee::find($this->selected_id);


        $employee->update([
            'ci' => $this->ci,
            'name' => $this->name,
            'lastname' => $this->lastname,
            'genero' => $this->genero,
            'dateNac' => $this->dateNac,
            'address' => $this->address,
            'phone' => $this->phone,
            'dateAdmission' => $this->dateAdmission,
            'area_trabajo_id' => $this->areaid
        ]);
        $employee->save();

        $this->resetUI();
        $this->emit('employee-updated', 'Datos de Empleado Actualizado');
    }

    // limpiar formulario
    public function resetUI(){
        $this->ci = '';
        $this->name = '';
        $this->lastname = '';
        $this->genero = 'Seleccionar';
        $this->dateNac = '';
        $this->address = '';
        $this->phone = '';
        $this->dateAdmission = '';
        $this->areaid = 'Elegir';
        $this->search = '';
        $this->selected_id = 0;
    }
    //
    protected $listeners = [
        'deleteRow' => 'Destroy'
    ];

    // eliminar informacion
    public function Destroy($id){
        $employee = Employee::find($id);
        $employee->delete();
        $this->resetUI();
        $this->emit('employee-deleted','Empleado Eliminado');
    }
}
