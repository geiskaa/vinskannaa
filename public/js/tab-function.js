/**
 * Tab functionality for various pages
 */

let activeTab = "orders";
let tabHistory = [];

function initializeTabs(defaultTab = "orders") {
    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    if (tabButtons.length === 0) return;

    // Set up tab button event listeners
    tabButtons.forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            const tabName = this.dataset.tab;
            console.log(`Tab button clicked: ${tabName}`);
            switchTab(tabName);
        });
    });

    // Initialize first tab as active if no default specified
    if (!defaultTab) {
        const firstTab = tabButtons[0];
        if (firstTab) {
            defaultTab = firstTab.dataset.tab;
        }
    }

    // Set initial active tab
    if (defaultTab) {
        switchTab(defaultTab);
    }

    // Handle browser back/forward buttons
    window.addEventListener("popstate", handlePopState);
}

function switchTab(tabName) {
    if (activeTab === tabName) return;

    const tabButtons = document.querySelectorAll(".tab-button");
    const tabContents = document.querySelectorAll(".tab-content");

    // Remove active class from all buttons
    tabButtons.forEach((btn) => {
        btn.classList.remove(
            "active",
            "text-blue-600",
            "border-blue-600",
            "bg-blue-50"
        );
        btn.classList.add("text-gray-500", "border-transparent");
    });

    // Add active class to clicked button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    console.log(`Switching to tab: ${tabName}`, activeButton);
    if (activeButton) {
        activeButton.classList.add(
            "active",
            "text-blue-600",
            "border-blue-600"
        );
        activeButton.classList.remove("text-gray-500", "border-transparent");

        // Add background color if needed
        if (activeButton.classList.contains("bg-hover")) {
            activeButton.classList.add("bg-blue-50");
        }
    }

    // Hide all tab contents
    tabContents.forEach((content) => {
        content.classList.add("hidden");
        content.classList.remove("fade-in");
    });

    // Show selected tab content with animation
    const activeContent = document.getElementById(tabName + "-tab");
    if (activeContent) {
        activeContent.classList.remove("hidden");

        // Add fade-in animation
        setTimeout(() => {
            activeContent.classList.add("fade-in");
        }, 10);
    }

    // Update active tab state
    const previousTab = activeTab;
    activeTab = tabName;

    // Add to history
    if (previousTab && previousTab !== tabName) {
        tabHistory.push(previousTab);
    }

    // Update URL hash without triggering page reload
    updateTabUrl(tabName);

    // Trigger custom event for tab change
    dispatchTabChangeEvent(tabName, previousTab);

    console.log(`Tab switched from ${previousTab} to ${tabName}`);
}

function updateTabUrl(tabName) {
    const currentUrl = new URL(window.location);

    // Update hash
    currentUrl.hash = tabName;

    // Update URL without page reload
    window.history.replaceState({ tab: tabName }, "", currentUrl);
}

function handlePopState(event) {
    const tabName = event.state?.tab || getTabFromHash();
    if (tabName && tabName !== activeTab) {
        switchTab(tabName);
    }
}

function getTabFromHash() {
    const hash = window.location.hash.replace("#", "");
    return hash || null;
}

function dispatchTabChangeEvent(newTab, oldTab) {
    const event = new CustomEvent("tabchange", {
        detail: {
            newTab: newTab,
            oldTab: oldTab,
            history: [...tabHistory],
        },
    });
    document.dispatchEvent(event);
}

function getActiveTab() {
    return activeTab;
}

function getTabHistory() {
    return [...tabHistory];
}

function goToPreviousTab() {
    if (tabHistory.length > 0) {
        const previousTab = tabHistory.pop();
        switchTab(previousTab);
    }
}

function clearTabHistory() {
    tabHistory = [];
}

// Advanced tab features
function enableTabKeyboardNavigation() {
    const tabButtons = document.querySelectorAll(".tab-button");

    tabButtons.forEach((button, index) => {
        button.addEventListener("keydown", (event) => {
            let targetIndex = index;

            switch (event.key) {
                case "ArrowRight":
                    event.preventDefault();
                    targetIndex = (index + 1) % tabButtons.length;
                    break;
                case "ArrowLeft":
                    event.preventDefault();
                    targetIndex =
                        (index - 1 + tabButtons.length) % tabButtons.length;
                    break;
                case "Home":
                    event.preventDefault();
                    targetIndex = 0;
                    break;
                case "End":
                    event.preventDefault();
                    targetIndex = tabButtons.length - 1;
                    break;
                case "Enter":
                case " ":
                    event.preventDefault();
                    button.click();
                    return;
            }

            if (targetIndex !== index) {
                tabButtons[targetIndex].focus();
            }
        });
    });
}

function addTabLoadingState(tabName) {
    const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
    const tabContent = document.getElementById(tabName + "-tab");

    if (tabButton) {
        tabButton.classList.add("opacity-75", "cursor-not-allowed");
        tabButton.disabled = true;
    }

    if (tabContent) {
        tabContent.classList.add("opacity-50");

        // Add loading spinner
        const spinner = document.createElement("div");
        spinner.className =
            "tab-loading-spinner absolute inset-0 flex items-center justify-center bg-white bg-opacity-75";
        spinner.innerHTML = `
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        `;

        tabContent.style.position = "relative";
        tabContent.appendChild(spinner);
    }
}

function removeTabLoadingState(tabName) {
    const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
    const tabContent = document.getElementById(tabName + "-tab");

    if (tabButton) {
        tabButton.classList.remove("opacity-75", "cursor-not-allowed");
        tabButton.disabled = false;
    }

    if (tabContent) {
        tabContent.classList.remove("opacity-50");

        // Remove loading spinner
        const spinner = tabContent.querySelector(".tab-loading-spinner");
        if (spinner) {
            spinner.remove();
        }
    }
}

function disableTab(tabName) {
    const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (tabButton) {
        tabButton.classList.add("opacity-50", "cursor-not-allowed");
        tabButton.disabled = true;
    }
}

function enableTab(tabName) {
    const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (tabButton) {
        tabButton.classList.remove("opacity-50", "cursor-not-allowed");
        tabButton.disabled = false;
    }
}

function addTabBadge(tabName, count, color = "bg-red-500") {
    const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (tabButton) {
        // Remove existing badge
        const existingBadge = tabButton.querySelector(".tab-badge");
        if (existingBadge) {
            existingBadge.remove();
        }

        // Add new badge if count > 0
        if (count > 0) {
            const badge = document.createElement("span");
            badge.className = `tab-badge absolute -top-2 -right-2 ${color} text-white text-xs rounded-full h-5 w-5 flex items-center justify-center`;
            badge.textContent = count > 99 ? "99+" : count;

            tabButton.style.position = "relative";
            tabButton.appendChild(badge);
        }
    }
}

function removeTabBadge(tabName) {
    const tabButton = document.querySelector(`[data-tab="${tabName}"]`);
    if (tabButton) {
        const badge = tabButton.querySelector(".tab-badge");
        if (badge) {
            badge.remove();
        }
    }
}

// CSS for fade-in animation (add to your CSS file)
const tabStyles = `
.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
`;

// Inject styles if not already present
if (!document.getElementById("tab-styles")) {
    const style = document.createElement("style");
    style.id = "tab-styles";
    style.textContent = tabStyles;
    document.head.appendChild(style);
}

// Make functions available globally
window.initializeTabs = initializeTabs;
window.switchTab = switchTab;
window.getActiveTab = getActiveTab;
window.getTabHistory = getTabHistory;
window.goToPreviousTab = goToPreviousTab;
window.clearTabHistory = clearTabHistory;
window.enableTabKeyboardNavigation = enableTabKeyboardNavigation;
window.addTabLoadingState = addTabLoadingState;
window.removeTabLoadingState = removeTabLoadingState;
window.disableTab = disableTab;
window.enableTab = enableTab;
window.addTabBadge = addTabBadge;
window.removeTabBadge = removeTabBadge;
