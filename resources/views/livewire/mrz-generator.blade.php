<div class="container-fluid h-100">
    <div class="row h-100" style="overflow-y: auto; overflow-x: hidden;">
        <!-- 📋 Left Panel: Form -->
        <div class="col-12 col-lg-4 py-4 px-5 bg-white shadow-sm">
            <h3 class="mb-4 text-primary text-center">🛂 Passport MRZ Generator</h3>

            <form wire:submit.prevent="generate" class="row g-3">
                <div class="col-12">
                    <label class="form-label">Passport Number:</label>
                    <input type="text" wire:model="passportNumber" class="form-control" />
                    @error("passportNumber")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12" x-data x-init="flatpickr($refs.birth, { dateFormat: 'Y-m-d' })">
                    <label class="form-label">Birth Date:</label>
                    <input x-ref="birth" type="text" class="form-control" wire:model.lazy="birthDateInput"
                        placeholder="YYYY-MM-DD">
                    <small class="text-muted">Will be auto-formatted to YYMMDD</small>
                    @error("birthDate")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12" x-data x-init="flatpickr($refs.expiry, { dateFormat: 'Y-m-d' })">
                    <label class="form-label">Expiry Date:</label>
                    <input x-ref="expiry" type="text" class="form-control" wire:model.lazy="expiryDateInput"
                        placeholder="YYYY-MM-DD">
                    <small class="text-muted">Will be auto-formatted to YYMMDD</small>
                    @error("expiryDate")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>



                <div class="col-12">
                    <label class="form-label">Country:</label>
                    <select wire:model="countryCode" class="form-select">
                        <option value="">-- Select Country --</option>
                        @foreach ($countries as $country => $code)
                            <option value="{{ $code }}">{{ $country }}</option>
                        @endforeach
                    </select>
                    @error("countryCode")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Gender:</label>
                    <select wire:model="gender" class="form-select">
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                    @error("gender")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Full Name:</label>
                    <input type="text" wire:model="name" class="form-control" />
                    @error("name")
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-grid">
                    <button type="submit" class="btn btn-primary mt-2">Generate MRZ</button>
                </div>
            </form>
        </div>

        <!-- 🖼️ Right Panel: Preview -->
        <div class="col-12 col-lg-8 d-flex align-items-center justify-content-center bg-light p-4">
            @if ($mrzResult)
                <pre class="border bg-white p-4 shadow" style="white-space: pre-wrap; font-family: monospace; font-size: 1.1rem;">
{{ $mrzResult }}
                </pre>
            @else
                <div class="text-muted">🧾 Fill in the form to generate your MRZ.</div>
            @endif
        </div>
    </div>
</div>
