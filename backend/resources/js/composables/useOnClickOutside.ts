import { onClickOutside as useVueUseOnClickOutside } from '@vueuse/core';

/**
 * Runs the handler when a click occurs outside the target element.
 * Use for closing dropdowns, popovers, or modals when clicking elsewhere.
 *
 * @param target - Ref to the element (e.g. dropdown container) that counts as "inside"
 * @param handler - Called when a click happens outside the target
 * @param options - Optional: ignore refs for elements that should not trigger close (e.g. trigger button if using separate ref)
 */
export function useOnClickOutside(
    target: Parameters<typeof useVueUseOnClickOutside>[0],
    handler: Parameters<typeof useVueUseOnClickOutside>[1],
    options?: Parameters<typeof useVueUseOnClickOutside>[2],
): ReturnType<typeof useVueUseOnClickOutside> {
    return useVueUseOnClickOutside(target, handler, options);
}
