<div class="grid sm:grid-cols-2 gap-4">

    <div>
        <label class="form-label">No. Rekam Medis <span class="text-red-500">*</span></label>
        <input type="text" name="medical_record_number"
               value="{{ old('medical_record_number', $patient?->medical_record_number) }}"
               class="form-input font-mono {{ $errors->has('medical_record_number') ? 'error' : '' }}"
               placeholder="cth. RM-2024-001" required>
        @error('medical_record_number')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Status <span class="text-red-500">*</span></label>
        <select name="status" class="form-input {{ $errors->has('status') ? 'error' : '' }}" required>
            <option value="active"     {{ old('status',$patient?->status)==='active'    ?'selected':'' }}>Aktif</option>
            <option value="discharged" {{ old('status',$patient?->status)==='discharged'?'selected':'' }}>Pulang</option>
            <option value="deceased"   {{ old('status',$patient?->status)==='deceased'  ?'selected':'' }}>Meninggal</option>
        </select>
        @error('status')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="name"
               value="{{ old('name', $patient?->name) }}"
               class="form-input {{ $errors->has('name') ? 'error' : '' }}"
               placeholder="Nama lengkap pasien" required>
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
        <select name="gender" class="form-input {{ $errors->has('gender') ? 'error' : '' }}" required>
            <option value="">Pilih...</option>
            <option value="male"   {{ old('gender',$patient?->gender)==='male'  ?'selected':'' }}>Laki-laki</option>
            <option value="female" {{ old('gender',$patient?->gender)==='female'?'selected':'' }}>Perempuan</option>
        </select>
        @error('gender')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Tanggal Lahir <span class="text-red-500">*</span></label>
        <input type="date" name="birth_date"
               value="{{ old('birth_date', $patient?->birth_date?->format('Y-m-d')) }}"
               class="form-input {{ $errors->has('birth_date') ? 'error' : '' }}"
               max="{{ date('Y-m-d') }}" required>
        @error('birth_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">No. Telepon</label>
        <input type="tel" name="phone"
               value="{{ old('phone', $patient?->phone) }}"
               class="form-input {{ $errors->has('phone') ? 'error' : '' }}"
               placeholder="08xx-xxxx-xxxx">
        @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="form-label">Tanggal Masuk</label>
        <input type="date" name="admission_date"
               value="{{ old('admission_date', $patient?->admission_date?->format('Y-m-d')) }}"
               class="form-input {{ $errors->has('admission_date') ? 'error' : '' }}">
        @error('admission_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="form-label">Diagnosa</label>
        <input type="text" name="diagnosis"
               value="{{ old('diagnosis', $patient?->diagnosis) }}"
               class="form-input {{ $errors->has('diagnosis') ? 'error' : '' }}"
               placeholder="cth. Stroke Iskemik, Stroke Hemoragik">
        @error('diagnosis')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="form-label">Alamat</label>
        <textarea name="address" rows="2"
                  class="form-input resize-none {{ $errors->has('address') ? 'error' : '' }}"
                  placeholder="Alamat lengkap pasien">{{ old('address', $patient?->address) }}</textarea>
        @error('address')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="sm:col-span-2">
        <label class="form-label">Catatan Klinis</label>
        <textarea name="notes" rows="2"
                  class="form-input resize-none {{ $errors->has('notes') ? 'error' : '' }}"
                  placeholder="Catatan tambahan (opsional)">{{ old('notes', $patient?->notes) }}</textarea>
        @error('notes')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

</div>
