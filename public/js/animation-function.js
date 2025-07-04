/**
 * Animation and visual effects functions
 */

/**
 * Add bounce animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function addBounceAnimation(element, duration = 600) {
    if (!element) return;

    element.classList.add("animate-bounce");
    setTimeout(() => {
        element.classList.remove("animate-bounce");
    }, duration);
}

/**
 * Add shake animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function addShakeAnimation(element, duration = 600) {
    if (!element) return;

    element.classList.add("animate-shake");
    setTimeout(() => {
        element.classList.remove("animate-shake");
    }, duration);
}

/**
 * Add pulse animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function addPulseAnimation(element, duration = 1000) {
    if (!element) return;

    element.classList.add("animate-pulse");
    setTimeout(() => {
        element.classList.remove("animate-pulse");
    }, duration);
}

/**
 * Add spin animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds (0 for infinite)
 */
function addSpinAnimation(element, duration = 0) {
    if (!element) return;

    element.classList.add("animate-spin");

    if (duration > 0) {
        setTimeout(() => {
            element.classList.remove("animate-spin");
        }, duration);
    }
}

/**
 * Remove spin animation from an element
 * @param {HTMLElement} element - Element to stop spinning
 */
function removeSpinAnimation(element) {
    if (!element) return;
    element.classList.remove("animate-spin");
}

/**
 * Add fade in animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function fadeIn(element, duration = 300) {
    if (!element) return;

    element.classList.add("opacity-0");
    element.classList.remove("hidden");

    setTimeout(() => {
        element.classList.remove("opacity-0");
        element.classList.add("opacity-100");
    }, 10);
}

/**
 * Add fade out animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 * @param {boolean} hide - Whether to hide the element after fade out
 */
function fadeOut(element, duration = 300, hide = true) {
    if (!element) return;

    element.classList.remove("opacity-100");
    element.classList.add("opacity-0");

    if (hide) {
        setTimeout(() => {
            element.classList.add("hidden");
        }, duration);
    }
}

/**
 * Add slide in from right animation
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function slideInFromRight(element, duration = 300) {
    if (!element) return;

    element.classList.add("translate-x-full", "opacity-0");
    element.classList.remove("hidden");

    setTimeout(() => {
        element.classList.remove("translate-x-full", "opacity-0");
        element.classList.add("translate-x-0", "opacity-100");
    }, 10);
}

/**
 * Add slide out to right animation
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 * @param {boolean} hide - Whether to hide the element after slide out
 */
function slideOutToRight(element, duration = 300, hide = true) {
    if (!element) return;

    element.classList.remove("translate-x-0", "opacity-100");
    element.classList.add("translate-x-full", "opacity-0");

    if (hide) {
        setTimeout(() => {
            element.classList.add("hidden");
        }, duration);
    }
}

/**
 * Add slide in from left animation
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function slideInFromLeft(element, duration = 300) {
    if (!element) return;

    element.classList.add("-translate-x-full", "opacity-0");
    element.classList.remove("hidden");

    setTimeout(() => {
        element.classList.remove("-translate-x-full", "opacity-0");
        element.classList.add("translate-x-0", "opacity-100");
    }, 10);
}

/**
 * Add slide out to left animation
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 * @param {boolean} hide - Whether to hide the element after slide out
 */
function slideOutToLeft(element, duration = 300, hide = true) {
    if (!element) return;

    element.classList.remove("translate-x-0", "opacity-100");
    element.classList.add("-translate-x-full", "opacity-0");

    if (hide) {
        setTimeout(() => {
            element.classList.add("hidden");
        }, duration);
    }
}

/**
 * Add slide in from top animation
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function slideInFromTop(element, duration = 300) {
    if (!element) return;

    element.classList.add("-translate-y-full", "opacity-0");
    element.classList.remove("hidden");

    setTimeout(() => {
        element.classList.remove("-translate-y-full", "opacity-0");
        element.classList.add("translate-y-0", "opacity-100");
    }, 10);
}

/**
 * Add slide out to top animation
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 * @param {boolean} hide - Whether to hide the element after slide out
 */
function slideOutToTop(element, duration = 300, hide = true) {
    if (!element) return;

    element.classList.remove("translate-y-0", "opacity-100");
    element.classList.add("-translate-y-full", "opacity-0");

    if (hide) {
        setTimeout(() => {
            element.classList.add("hidden");
        }, duration);
    }
}

/**
 * Add scale animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} scale - Scale factor (e.g., 1.1 for 110%)
 * @param {number} duration - Animation duration in milliseconds
 */
function scaleElement(element, scale = 1.1, duration = 200) {
    if (!element) return;

    element.style.transform = `scale(${scale})`;
    element.style.transition = `transform ${duration}ms ease-in-out`;

    setTimeout(() => {
        element.style.transform = "scale(1)";
        setTimeout(() => {
            element.style.transform = "";
            element.style.transition = "";
        }, duration);
    }, duration);
}

/**
 * Add wiggle animation to an element
 * @param {HTMLElement} element - Element to animate
 * @param {number} duration - Animation duration in milliseconds
 */
function wiggleElement(element, duration = 600) {
    if (!element) return;

    const keyframes = [
        { transform: "rotate(0deg)" },
        { transform: "rotate(3deg)" },
        { transform: "rotate(-3deg)" },
        { transform: "rotate(3deg)" },
        { transform: "rotate(-3deg)" },
        { transform: "rotate(0deg)" },
    ];

    element.animate(keyframes, {
        duration: duration,
        easing: "ease-in-out",
    });
}

/**
 * Add glow effect to an element
 * @param {HTMLElement} element - Element to add glow effect
 * @param {string} color - Glow color (e.g., 'blue', 'green', 'red')
 * @param {number} duration - Glow duration in milliseconds (0 for permanent)
 */
function addGlowEffect(element, color = "blue", duration = 0) {
    if (!element) return;

    const glowClass = `shadow-glow-${color}`;
    element.classList.add(glowClass);

    if (duration > 0) {
        setTimeout(() => {
            element.classList.remove(glowClass);
        }, duration);
    }
}

/**
 * Remove glow effect from an element
 * @param {HTMLElement} element - Element to remove glow effect
 * @param {string} color - Glow color to remove
 */
function removeGlowEffect(element, color = "blue") {
    if (!element) return;

    const glowClass = `shadow-glow-${color}`;
    element.classList.remove(glowClass);
}

/**
 * Add ripple effect to an element
 * @param {HTMLElement} element - Element to add ripple effect
 * @param {Event} event - Click event
 * @param {string} color - Ripple color
 */
function addRippleEffect(element, event, color = "rgba(255, 255, 255, 0.6)") {
    if (!element) return;

    const rect = element.getBoundingClientRect();
    const ripple = document.createElement("span");
    const size = Math.max(rect.width, rect.height);
    const x = event.clientX - rect.left - size / 2;
    const y = event.clientY - rect.top - size / 2;

    ripple.style.cssText = `
        position: absolute;
        width: ${size}px;
        height: ${size}px;
        left: ${x}px;
        top: ${y}px;
        background-color: ${color};
        border-radius: 50%;
        pointer-events: none;
        transform: scale(0);
        animation: ripple 0.6s ease-out;
    `;

    // Add CSS animation if not exists
    if (!document.querySelector("#ripple-animation")) {
        const style = document.createElement("style");
        style.id = "ripple-animation";
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Ensure element has relative position
    const position = window.getComputedStyle(element).position;
    if (position === "static") {
        element.style.position = "relative";
    }

    element.appendChild(ripple);

    // Remove ripple after animation
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

/**
 * Animate number counting
 * @param {HTMLElement} element - Element to animate
 * @param {number} start - Starting number
 * @param {number} end - Ending number
 * @param {number} duration - Animation duration in milliseconds
 * @param {boolean} useCommas - Whether to use comma separators
 */
function animateCounter(
    element,
    start,
    end,
    duration = 2000,
    useCommas = true
) {
    if (!element) return;

    const startTime = performance.now();
    const difference = end - start;

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function (ease-out)
        const easeOut = 1 - Math.pow(1 - progress, 3);

        const current = Math.floor(start + difference * easeOut);

        if (useCommas) {
            element.textContent = current.toLocaleString();
        } else {
            element.textContent = current;
        }

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        }
    }

    requestAnimationFrame(updateCounter);
}

// Make functions available globally
window.addBounceAnimation = addBounceAnimation;
window.addShakeAnimation = addShakeAnimation;
window.addPulseAnimation = addPulseAnimation;
window.addSpinAnimation = addSpinAnimation;
window.removeSpinAnimation = removeSpinAnimation;
window.fadeIn = fadeIn;
window.fadeOut = fadeOut;
window.slideInFromRight = slideInFromRight;
window.slideOutToRight = slideOutToRight;
window.slideInFromLeft = slideInFromLeft;
window.slideOutToLeft = slideOutToLeft;
window.slideInFromTop = slideInFromTop;
window.slideOutToTop = slideOutToTop;
window.scaleElement = scaleElement;
window.wiggleElement = wiggleElement;
window.addGlowEffect = addGlowEffect;
window.removeGlowEffect = removeGlowEffect;
window.addRippleEffect = addRippleEffect;
window.animateCounter = animateCounter;
