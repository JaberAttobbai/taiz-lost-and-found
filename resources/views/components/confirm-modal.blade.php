{{-- Confirmation Modal Component --}}
@if(session('success') || session('error') || true)
<div
    x-data="confirmModal()"
    @open-confirm.window="openConfirm($event.detail)"
    x-show="open"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="cancel()"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
    ></div>

    {{-- Modal Card --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-90 translate-y-4"
        class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden"
    >
        <div class="p-8 text-center">
            {{-- Icon --}}
            <div
                class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5"
                :class="{
                    'bg-red-50': type === 'danger',
                    'bg-primary/10': type === 'success',
                    'bg-yellow-50': type === 'warning'
                }"
            >
                <template x-if="type === 'danger'">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </template>
                <template x-if="type === 'success'">
                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </template>
                <template x-if="type === 'warning'">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </template>
            </div>

            <h3 class="text-xl font-bold text-brand-text mb-2" x-text="title"></h3>
            <p class="text-gray-500 text-sm leading-relaxed" x-text="message"></p>
        </div>

        {{-- Buttons --}}
        <div class="flex border-t border-gray-100">
            <button
                @click="cancel()"
                class="flex-1 py-4 text-gray-600 font-bold hover:bg-gray-50 transition-colors border-l border-gray-100 rounded-bl-3xl"
            >
                إلغاء
            </button>
            <button
                @click="doConfirm()"
                class="flex-1 py-4 font-bold transition-all rounded-br-3xl"
                :class="{
                    'text-red-600 hover:bg-red-50': type === 'danger',
                    'text-primary hover:bg-primary/5': type === 'success',
                    'text-yellow-600 hover:bg-yellow-50': type === 'warning'
                }"
                x-text="confirmText"
            ></button>
        </div>
    </div>
</div>

<script>
function confirmModal() {
    return {
        open: false,
        type: 'warning',
        title: '',
        message: '',
        confirmText: 'تأكيد',
        _formId: null,

        openConfirm(detail) {
            this.type        = detail.type        || 'warning';
            this.title       = detail.title       || 'هل أنت متأكد؟';
            this.message     = detail.message     || 'يرجى تأكيد هذه العملية.';
            this.confirmText = detail.confirmText || 'تأكيد';
            this._formId     = detail.formId      || null;
            this.open = true;
        },

        doConfirm() {
            this.open = false;
            this.$nextTick(() => {
                if (this._formId) {
                    const form = document.getElementById(this._formId);
                    if (form) form.submit();
                }
            });
        },

        cancel() {
            this.open = false;
            this._formId = null;
        }
    };
}

// Global helper — call this from any @click handler
function askConfirm(formId, options) {
    window.dispatchEvent(new CustomEvent('open-confirm', {
        detail: { formId: formId, ...options }
    }));
}
</script>
@endif
