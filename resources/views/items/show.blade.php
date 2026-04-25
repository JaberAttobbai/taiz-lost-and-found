<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-brand-text leading-tight">
                تفاصيل الإعلان
            </h2>
            <a href="{{ route('home') }}" class="text-sm font-bold text-gray-500 hover:text-primary transition-colors flex items-center gap-1">
                <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                العودة للقائمة
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg shadow-gray-200/50 sm:rounded-2xl border border-gray-100 transition-all">
                
                <!-- Image Section -->
                @if($item->image_path)
                    <div class="w-full h-[400px] bg-brand-bg relative overflow-hidden group">
                        <img src="{{ Storage::url($item->image_path) }}" alt="{{ $item->title }}" class="w-full h-full object-contain transform transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                @else
                    <div class="w-full h-64 bg-brand-bg flex flex-col items-center justify-center text-gray-400 border-b border-gray-100">
                        <svg class="w-20 h-20 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-medium">لا توجد صورة مرفقة</span>
                    </div>
                @endif

                <div class="p-8 sm:p-10">
                    <!-- Header Info -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                @if($item->type === 'lost')
                                    <span class="px-4 py-1.5 text-sm font-bold bg-red-50 text-red-600 rounded-full border border-red-100 shadow-sm">مفقود</span>
                                @else
                                    <span class="px-4 py-1.5 text-sm font-bold bg-primary/10 text-primary-dark rounded-full border border-primary/20 shadow-sm">موجود</span>
                                @endif

                                @if($item->status === 'returned')
                                    <span class="px-4 py-1.5 text-sm font-bold bg-gray-100 text-gray-600 rounded-full shadow-sm">تم الاسترجاع</span>
                                @endif
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-bold text-brand-text mb-3 leading-tight">{{ $item->title }}</h1>
                            <div class="flex flex-wrap gap-2 text-sm text-gray-500 items-center font-medium">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    نُشر {{ $item->created_at->diffForHumans() }}
                                </span>
                                <span class="text-gray-300">&bull;</span>
                                <span class="text-primary font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $item->neighborhood->name }}
                                </span>
                                <span class="text-gray-300">&bull;</span>
                                <span class="text-primary font-bold flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $item->category->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="prose max-w-none text-gray-600 mb-10 whitespace-pre-wrap leading-relaxed text-lg bg-brand-bg/50 p-6 rounded-2xl border border-gray-50">
                        {{ $item->description }}
                    </div>

                    <!-- Contact & Poster Info -->
                    <div class="bg-gradient-to-br from-primary/5 to-primary/10 rounded-2xl p-8 border border-primary/20 flex flex-col md:flex-row justify-between items-center gap-6 shadow-inner">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-white text-primary flex items-center justify-center text-xl font-bold shadow-md border border-primary/10">
                                {{ mb_substr($item->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm text-primary font-bold mb-1">الناشر</p>
                                <p class="font-bold text-brand-text text-xl">{{ $item->user->name }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <!-- Phone Button -->
                            <a href="tel:{{ $item->contact_phone }}" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 text-lg font-bold text-brand-text hover:text-primary bg-white px-6 py-3 rounded-xl shadow-md hover:shadow-lg border border-primary/20 transform transition-all duration-300 hover:-translate-y-1" dir="ltr">
                                <span>{{ $item->contact_phone }}</span>
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </a>
                            
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $item->contact_phone) }}" target="_blank" class="flex-1 md:flex-none inline-flex items-center justify-center gap-2 text-lg font-bold text-white bg-gradient-to-r from-[#25D366] to-[#128C7E] px-6 py-3 rounded-xl shadow-md shadow-[#25D366]/30 hover:shadow-lg hover:shadow-[#25D366]/50 transform transition-all duration-300 hover:-translate-y-1">
                                <span>تواصل واتساب</span>
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Actions (Only for owner) -->
                    @if(auth()->id() === $item->user_id)
                        <div class="mt-10 flex flex-wrap gap-4 pt-8 border-t border-gray-100">
                            <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border-2 border-primary text-primary font-bold rounded-xl hover:bg-primary hover:text-white transition-colors duration-300">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                تعديل الإعلان
                            </a>
                            
                            <form action="{{ route('items.destroy', $item) }}" method="POST" id="delete-item-{{ $item->id }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="button"
                                    onclick="askConfirm('delete-item-{{ $item->id }}', {
                                        type: 'danger',
                                        title: 'حذف الإعلان نهائياً؟',
                                        message: 'سيتم حذف هذا الإعلان بشكل دائم ولا يمكن استعادته لاحقاً. هل أنت متأكد من رغبتك في الحذف؟',
                                        confirmText: 'نعم، احذف نهائياً'
                                    })"
                                    class="inline-flex items-center justify-center px-6 py-3 bg-red-50 text-red-600 border-2 border-red-100 font-bold rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors duration-300">
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    حذف نهائياً
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
