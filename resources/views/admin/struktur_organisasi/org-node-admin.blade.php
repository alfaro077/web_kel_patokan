<li>
    <div class="inline-block relative z-10 group">
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex flex-col items-center text-center w-56 hover:border-emerald-300 hover:shadow-md transition mx-auto relative">
            
            {{-- Aksi Tambah Bawahan, Edit & Delete (muncul saat hover) --}}
            <div class="absolute -top-3 -right-3 hidden group-hover:flex items-center gap-1 bg-white p-1 rounded-xl shadow-md border border-slate-200 z-20">
                <a href="{{ route('admin.struktur_organisasi.create', ['parent_id' => $member->id]) }}" class="p-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg transition-colors" title="Tambah Bawahan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </a>
                <a href="{{ route('admin.struktur_organisasi.edit', $member->id) }}" class="p-1.5 bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg transition-colors" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </a>
                <form action="{{ route('admin.struktur_organisasi.destroy', $member->id) }}" method="POST" class="inline m-0" onsubmit="event.preventDefault(); window.ajaxDelete(this.action, document.querySelector('meta[name=csrf-token]').getAttribute('content'), 'Yakin ingin menghapus anggota ini beserta jabatannya?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors" title="Hapus">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>

            <div class="w-16 h-16 rounded-full border-2 border-slate-800 p-0.5 mb-3 bg-slate-50 shrink-0 mx-auto overflow-hidden">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover rounded-full">
                @else
                    <div class="w-full h-full bg-slate-200 rounded-full flex items-center justify-center text-slate-400">
                        <i class="fas fa-user text-xl"></i>
                    </div>
                @endif
            </div>
            
            <h3 class="font-bold text-xs text-slate-900 leading-snug break-words w-full">
                {{ $member->name }}
            </h3>
            
            <div class="mt-1 w-full">
                <span class="bg-slate-900 text-white font-extrabold text-[9px] uppercase px-2 py-0.5 rounded-md tracking-wider inline-block">
                    {{ $member->position }}
                </span>
            </div>
        </div>
    </div>

    @if($member->childrenRecursive && $member->childrenRecursive->count() > 0)
        <ul>
            @foreach($member->childrenRecursive as $child)
                @include('admin.struktur_organisasi.org-node-admin', ['member' => $child])
            @endforeach
        </ul>
    @endif
</li>
