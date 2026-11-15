@php $isSelf = isset($user) && $user->id_user === auth()->id(); @endphp
<div class="space-y-4">
    <div>
        <label class="block text-sm mb-1">Nama</label>
        <input type="text" name="nama_user"
               value="{{ old('nama_user', $user->nama_user ?? '') }}"
               class="w-full border rounded px-3 py-2">
        @error('nama_user') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">Username</label>
        <input type="text" name="username"
               value="{{ old('username', $user->username ?? '') }}"
               class="w-full border rounded px-3 py-2">
        @error('username') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">
            Password @isset($user) <span class="text-gray-500">(kosongkan jika tidak diganti)</span> @endisset
        </label>
        <input type="password" name="password" class="w-full border rounded px-3 py-2">
        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">Level</label>
        <select name="level" @disabled($isSelf) class="w-full border rounded px-3 py-2 @if($isSelf) bg-gray-100 cursor-not-allowed @endif">
            @foreach (['admin', 'kasir'] as $l)
                <option value="{{ $l }}" @selected(old('level', $user->level ?? '') === $l)>{{ ucfirst($l) }}</option>
            @endforeach
        </select>
        @if($isSelf) <p class="text-gray-500 text-sm mt-1">Anda tidak dapat mengubah level akun sendiri.</p> @endif
        @error('level') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm mb-1">Status</label>
        <select name="status" @disabled($isSelf) class="w-full border rounded px-3 py-2 @if($isSelf) bg-gray-100 cursor-not-allowed @endif">
            @foreach (['aktif', 'nonaktif'] as $s)
                <option value="{{ $s }}" @selected(old('status', $user->status ?? 'aktif') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        @if($isSelf) <p class="text-gray-500 text-sm mt-1">Anda tidak dapat mengubah status akun sendiri.</p> @endif
        @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex gap-2">
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        <a href="{{ route('user.index') }}" class="px-4 py-2 rounded border">Batal</a>
    </div>
</div>