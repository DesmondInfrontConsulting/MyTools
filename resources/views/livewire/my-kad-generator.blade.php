<div class="container-fluid h-100">
    <div class="row h-100" style="overflow-y: auto; overflow-x: hidden;">
        <!-- 📋 Left Panel: Form -->
        <div class="col-12 col-lg-4 py-4 px-5 bg-white shadow-sm">
            <h3 class="mb-4 text-primary text-center">🆔 MyKad Generator</h3>

            <form wire:submit.prevent="generate" enctype="multipart/form-data" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Photo:</label>
                    <input type="file" wire:model="photo" class="form-control">
                    <small class="text-muted">Recommended image size: 172 x 228 pixels</small>
                    @error("photo")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Template:</label>
                    <select wire:model="template" wire:click="updateTemplate" class="form-select">
                        <option value="template.png">Template 1</option>
                        <option value="template2.jpg">Template 2</option>
                    </select>
                    @error("template")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Name:</label>
                    <input type="text" wire:model="name" name="name" class="form-control">
                    @error("name")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12" x-data="{ rawIC: '' }">
                    <label class="form-label">IC Number:</label>
                    <input type="text" x-ref="input" name="ic_number"
                        x-on:input="
            let v = $refs.input.value.replace(/\D/g, '');
            if (v.length > 6) v = v.slice(0, 6) + '-' + v.slice(6);
            if (v.length > 9) v = v.slice(0, 9) + '-' + v.slice(9, 13);
            $refs.input.value = v;
            rawIC = v.replace(/-/g, '');
            $dispatch('input', v);
        "
                        wire:model.lazy="id_number" class="form-control" maxlength="14">
                    <small class="text-muted">IC Number format: yymmdd-xx-xxxx</small>
                    @error("id_number")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                    <div class="mt-2">
                        <strong>Without dash:</strong> <span x-text="rawIC"></span>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Address:</label>
                    <small class="text-muted">Maximum 5 lines</small>
                    <textarea wire:model="address" class="form-control" rows="5" wire:model="address" name="street-address"
                        id="address" rows="5" class="form-control" autocomplete="street-address"></textarea>
                    @error("address")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-grid">
                    <button type="submit" class="btn btn-primary mt-2" wire:click="generate" data-bs-toggle="modal"
                        data-bs-target="#loadingBackdrop">Generate</button>
                    @if ($downloadUrl)
                        <a href="{{ $downloadUrl }}" download="{{ $id_number }}.png" class="btn btn-success mt-3"
                            download>⬇️
                            Download MyKad</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 🖼️ Right Panel: Preview -->
        <div class="col-12 col-lg-8 d-flex align-items-center justify-content-center bg-light p-4   ">
            @if ($downloadUrl)
                <div class="text-center">
                    <img src="{{ $downloadUrl }}" class=" shadow border" style="max-height: 95vh;">
                </div>
            @else
                <img wire:key="preview-{{ $template }}" src="{{ asset($template) }}" class=" shadow border"
                    style="max-height: 95vh;">
            @endif
        </div>
    </div>
</div>
