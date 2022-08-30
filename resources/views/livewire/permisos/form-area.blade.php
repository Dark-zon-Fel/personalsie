<div wire:ignore.self class="modal fade" id="theModal-area" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark">
                <h5 class="modal-title text-white">
                    <b>{{ $componentNameArea }}</b> | {{ 'CREAR' }}
                </h5>
                <h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <span class="fas fa-edit"></span>
                                </span>
                            </div>
                            <input type="text" wire:model.lazy="name" class="form-control" placeholder="ej: Administracion" maxlength="255">
                        </div>
                        @error('name')<span class="text-danger er">{{ $message }}</span> @enderror
                        <br>
                        <!-- -->
                       
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="resetUI()" class="btn btn-warning close-btn text-info"
                    data-dismiss="modal" style="background: #3b3f5c">CANCELAR</button>
                @if ($selected_id < 1)
                    <button type="button" wire:click.prevent="StoreArea()"
                        class="btn btn-warning close-btn text-info">GUARDAR</button>
                @else
                    <button type="button" wire:click.prevent="Update()"
                        class="btn btn-warning close-btn text-info">ACTUALIZAR</button>
                @endif


            </div>
        </div>
    </div>
</div>
