<div class="row sales layout-top-spacing">
    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{ $componentName }} | {{ $pageTitle }}</b>
                </h4>
                <ul class="tabs tab-pills">
                    <a href="javascript:void(0)" class="btn btn-warning" data-toggle="modal"
                        data-target="#theModal">Agregar</a>
                </ul>
                {{-- Cambio de Prueba Commit  ss--}}
               {{-- <h6>{{ date('Y-m-d H:i:s') }}</h6> muestra hora de sistema--}}
            </div>
            @include('common.searchbox')

            <div class="widget-content">
                <div class="table-responsive">
                    <table class="table table-hover table table-bordered table-bordered-bd-warning mt-4">
                        <thead class="text-white" style="background: #ee761c">
                            <tr>
                                <th class="table-th text-withe">NOMBRE</th>
                                <th class="table-th text-withe text-center">APELLIDOS</th>
                                <th class="table-th text-withe text-center">CI</th>
                                <th class="table-th text-withe text-center">SEXO</th>
                                <th class="table-th text-withe text-center">FECHA DE NAC.</th>
                                <th class="table-th text-withe text-center">DIRECCION</th>
                                <th class="table-th text-withe text-center">TELEFONO</th>
                                <th class="table-th text-withe text-center">FACHA DE ADMISION</th>

                                <th class="table-th text-withe text-center">TIEMPO TRANCURRIDO</th>  {{-- fecha de admicion menos fecha actual y mostrar --}}

                                <th class="table-th text-withe text-center">AREA</th>
                                <th class="table-th text-withe text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $employee)
                                <tr>
                                    <td><h6 class="text-center">{{ $employee->name }}</h6></td>
                                    <td><h6 class="text-center">{{ $employee->lastname }}</h6></td>
                                    <td><h6 class="text-center">{{ $employee->ci }}</h6></td>
                                    <td class="text-center">
                                        <span class="badge {{$employee->genero == 'Masculino' ? 'badge-success' : 'badge-danger'}}
                                            text-uppercase" style="background-color: #fff; color: black">
                                            {{$employee->genero}}
                                        </span>
                                    </td>
                                    <td><h6 class="text-center">{{ $employee->dateNac }}</h6></td>
                                    <td><h6 class="text-center">{{ $employee->address }}</h6></td>
                                    <td><h6 class="text-center">{{ $employee->phone }}</h6></td>
                                    <td><h6 class="text-center">{{ $employee->dateAdmission }}</h6></td>

                                    {{--<td><h6 class="text-center">{{ $employee->created_at->diffForHumans() }}</h6></td>--}}
                                    <td><h6 class="text-center">{{$tiempos}}</h6></td>

                                    <td><h6 class="text-center">{{ $employee->area }}</h6></td>

                                    <td class="text-center">
                                        <a href="javascript:void(0)" wire:click="Edit({{ $employee->id }})"
                                            class="btn btn-dark mtmobile" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)"
                                            wire:click="Destroy({{$employee->id}})"
                                            class="btn btn-dark" title="Destroy">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
     @include('livewire.employee.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('employee-added', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('employee-updated', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('employee-deleted', msg => {
            ///
        });
        window.livewire.on('modal-show', msg => {
            $('#theModal').modal('show')
        });
        window.livewire.on('modal-hide', msg => {
            $('#theModal').modal('hide')
        });
        window.livewire.on('hidden.bs.modal', msg => {
            $('.er').css('display','none')
        });

    });

    function Confirm(id) {
        swal.fire({
            title: 'CONFIRMAR',
            icon: 'warning',
            text: 'Confirmar eliminar el empleado',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',
            cancelButtonColor: '#383838',
            confirmButtonColor: '#3B3F5C',
            confirmButtonText: 'Aceptar'
        }).then(function(result) {
            if (result.value) {
                window.livewire.emit('deleteRow', id)
                Swal.close()
            }
        })
    }
</script>
