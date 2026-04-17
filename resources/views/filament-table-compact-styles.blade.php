<style>
    /* Global table text compaction for Filament: prevents long labels from pushing other columns */
    .fi-ta table td .fi-ta-text,
    .fi-ta table td .fi-ta-text-item-label,
    .fi-ta table td [class*="fi-ta-text"] {
        display: block;
        max-width: 50ch;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Keep utility columns compact */
    .fi-ta table td.fi-ta-col-actions,
    .fi-ta table th.fi-ta-col-actions,
    .fi-ta table td.fi-ta-col-select,
    .fi-ta table th.fi-ta-col-select {
        width: 1%;
        white-space: nowrap;
    }

    @media (max-width: 1024px) {
        .fi-ta table td .fi-ta-text,
        .fi-ta table td .fi-ta-text-item-label,
        .fi-ta table td [class*="fi-ta-text"] {
            max-width: 50ch;
        }
    }
</style>

<script>
    (function () {
        const selector = '.fi-ta table td .fi-ta-text, .fi-ta table td .fi-ta-text-item-label, .fi-ta table td [class*="fi-ta-text"]';

        const applyTooltipToTruncatedCells = () => {
            document.querySelectorAll(selector).forEach((element) => {
                const text = (element.textContent || '').trim();

                if (!text) {
                    element.removeAttribute('title');
                    return;
                }

                const isTruncated = element.scrollWidth > element.clientWidth;
                const isLongText = text.length > 50;

                if (isTruncated || isLongText) {
                    element.setAttribute('title', text);
                } else {
                    element.removeAttribute('title');
                }
            });
        };

        document.addEventListener('DOMContentLoaded', applyTooltipToTruncatedCells);
        document.addEventListener('livewire:navigated', applyTooltipToTruncatedCells);
        document.addEventListener('livewire:initialized', applyTooltipToTruncatedCells);

        const observer = new MutationObserver(() => applyTooltipToTruncatedCells());
        observer.observe(document.documentElement, { childList: true, subtree: true });
    })();
</script>
