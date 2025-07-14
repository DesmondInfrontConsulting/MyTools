<div x-data="loadModal" x-init="$watch('showModal', value => { value ? '' : closeModal(); });" wire:ignore>
    <div>
        <div class="modal loading-overlay" id="loadingBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="loadingBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered justify-content-center">
                <div class="modal-content border-0 bg-transparent shadow-none">
                    <div class="modal-body text-center">
                        <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-3 text-white fw-bold">Loading, please wait...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script type="module">
        Alpine.data('loadModal', () => {
            return {
                showModal: @entangle("showModal"),
                closeModal() {
                    const loadingModal = document.getElementById('loadingBackdrop');
                    const modal = bootstrap.Modal.getInstance(loadingModal) || new bootstrap.Modal(loadingModal);
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    modal.hide();
                    this.showModal = true;
                },
            }
        })
    </script>
@endscript
