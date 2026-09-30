<li>
    <div class="inline-block relative z-10">
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex flex-col items-center text-center w-52 hover:border-slate-300 hover:shadow-md transition mx-auto">
            <div class="w-20 h-20 rounded-full border-2 border-slate-800 p-0.5 mb-3 bg-slate-50 shrink-0 shadow-sm mx-auto overflow-hidden">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" class="w-full h-full object-cover rounded-full">
                @else
                    <div class="w-full h-full bg-slate-200 rounded-full flex items-center justify-center text-slate-400">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                @endif
            </div>
            
            <h3 class="font-bold text-xs text-slate-900 leading-snug break-words w-full">
                {{ $member->name }}
            </h3>
            
            @if($member->nip)
                <div class="text-[10px] text-slate-500 mt-1">NIP: {{ $member->nip }}</div>
            @endif

            <div class="mt-2 w-full">
                <span class="bg-slate-900 text-white font-extrabold text-[9px] uppercase px-2.5 py-1 rounded-md tracking-wider inline-block">
                    {{ $member->position }}
                </span>
            </div>
            
            @if($member->tupoksi)
                <button type="button" 
                        @click="$dispatch('open-tupoksi', { name: '{{ addslashes($member->name) }}', position: '{{ addslashes($member->position) }}', tupoksi: '{{ addslashes(preg_replace('/\r|\n/', '', nl2br(e($member->tupoksi)))) }}' })"
                        class="mt-3 pt-3 border-t border-slate-100 text-[10px] text-blue-600 font-bold hover:text-blue-800 transition-colors w-full text-center flex items-center justify-center gap-1">
                    <i class="fas fa-info-circle"></i> Lihat Tupoksi
                </button>
            @endif
        </div>
    </div>

    @if($member->childrenRecursive && $member->childrenRecursive->count() > 0)
        <ul>
            @foreach($member->childrenRecursive as $child)
                @include('partials.org-node', ['member' => $child])
            @endforeach
        </ul>
    @endif
</li>
