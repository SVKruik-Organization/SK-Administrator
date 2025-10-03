<script lang="ts" setup>
import { PromptTypes, type LoginResponse, type PopupItem, type UserData } from "~/assets/customTypes";
import { useSideBarStore } from "~/stores/SideBarStore";
import { useFetchLoginEmail } from "~/utils/fetch/auth/useFetchLoginEmail";
import { useFetchLoginGuest } from "~/utils/fetch/auth/useFetchLoginGuest";
import { useFetchSubmit2FA } from "~/utils/fetch/auth/useFetchSubmit2FA";
const { $event } = useNuxtApp();
const userStore = useUserStore();
const sideBarStore = useSideBarStore();

// Reactive Data
const emailInput: Ref<string> = ref("");
const passwordInput: Ref<string> = ref("");
const verificationInput: Ref<string> = ref("");
const guestInput: Ref<string> = ref("");
const step: Ref<1 | 2 | 3> = ref(1);
const loginButton = useTemplateRef<HTMLButtonElement>("loginButton");
const verificationButton = useTemplateRef<HTMLButtonElement>("verificationButton");
const guestButton = useTemplateRef<HTMLButtonElement>("guestButton");
const isSubmissionInProgress: Ref<boolean> = ref(false);
const username: Ref<string> = ref("Administrator");

// Methods

/**
 * Try a user login with email and password.
 * If successful, move to step 2 (2FA).
 * @returns Status of the operation.
 */
async function submitLogin(): Promise<boolean> {
    try {
        if (isSubmissionInProgress.value) return false;
        toggleButtonState(loginButton.value, true);

        if (!emailInput.value.length || !passwordInput.value.length) throw new Error("The form is not completed correctly. Please try again.");
        username.value = await useFetchLoginEmail(emailInput.value, passwordInput.value);
        step.value = 2;
        return true;
    } catch (error: any) {
        $event("popup", {
            id: createTicket(4),
            type: PromptTypes.danger,
            message: error.message || "Something went wrong on our end. Please try again later.",
            duration: 3,
        } as PopupItem);
        return false;
    } finally {
        toggleButtonState(loginButton.value, false);
    }
}

/**
 * Try to submit the 2FA code for verification.
 * If successful, log the user in and redirect to the dashboard.
 * @returns Status of the operation.
 */
async function submit2fa(): Promise<boolean> {
    try {
        if (isSubmissionInProgress.value) return false;
        toggleButtonState(verificationButton.value, true);

        if (!emailInput.value.length || !verificationInput.value.length) throw new Error("The form is not completed correctly. Please try again.");
        const response: LoginResponse = await useFetchSubmit2FA(emailInput.value, verificationInput.value);
        const { user, ...rest } = response;
        userStore.setUser(user);
        sideBarStore.setSideBar(rest);

        $event("popup", {
            id: createTicket(4),
            type: PromptTypes.success,
            message: `Login successful! Welcome back ${userStore.user?.firstName}.`,
            duration: 3,
        } as PopupItem);
        navigateTo("/panel");
        return true;
    } catch (error: any) {
        $event("popup", {
            id: createTicket(4),
            type: PromptTypes.danger,
            message: error.message || "Something went wrong on our end. Please try again later.",
            duration: 3,
        } as PopupItem);
        return false;
    } finally {
        toggleButtonState(verificationButton.value, false);
    }
}

/**
 * Try a guest user using a code.q
 * @returns Status of the operation.
 */
async function submitGuest(): Promise<boolean> {
    try {
        if (isSubmissionInProgress.value) return false;
        toggleButtonState(guestButton.value, true);

        if (!guestInput.value.length) throw new Error("The form is not completed correctly. Please try again.");
        const response: UserData = await useFetchLoginGuest(guestInput.value);
        userStore.setUser(response);

        $event("popup", {
            id: createTicket(4),
            type: PromptTypes.success,
            message: `Login successful! Welcome ${userStore.user?.firstName}.`,
            duration: 3,
        } as PopupItem);
        navigateTo("/panel");
        return true;
    } catch (error: any) {
        $event("popup", {
            id: createTicket(4),
            type: PromptTypes.danger,
            message: error.message || "Something went wrong on our end. Please try again later.",
            duration: 3,
        } as PopupItem);
        return false;
    } finally {
        toggleButtonState(guestButton.value, false);
    }
}

/**
 * Toggle the state of a button (disabled/enabled) and update its appearance.
 * @param button The button element to toggle.
 * @param disabled Whether to disable (true) or enable (false) the button.
 */
function toggleButtonState(button: HTMLButtonElement | null, disabled: boolean) {
    isSubmissionInProgress.value = disabled;
    if (!button) return;
    button.disabled = disabled;
    button.style.pointerEvents = disabled ? "none" : "auto";
    button.style.opacity = disabled ? "0.6" : "1";
    const iconElement = button.querySelector("i");
    if (iconElement) {
        if (disabled) {
            iconElement.classList.replace("fa-arrow-right-to-bracket", "fa-circle-notch");
            iconElement.classList.add("fa-spin");
        } else {
            iconElement.classList.replace("fa-circle-notch", "fa-arrow-right-to-bracket");
            iconElement.classList.remove("fa-spin");
        }
    }
}
</script>

<template>
    <main class="flex">
        <div class="flex content-container">
            <div class="flex-col background-image-container">
                <div class="background-image"></div>
                <h1>SK Administrator</h1>
            </div>
            <form class="flex-col" @submit.prevent="submitLogin" v-if="step === 1">
                <div class="flex-col title">
                    <h1>Welcome back,</h1>
                    <strong>Administrator</strong>
                </div>
                <div class="flex input-container">
                    <label for="email" v-if="!emailInput.length" class="flex">
                        Email<span>*</span>
                    </label>
                    <input type="email" v-model="emailInput" required id="email" name="email" />
                </div>
                <div class="flex input-container">
                    <label for="password" v-if="!passwordInput.length" class="flex">
                        Password<span>*</span>
                    </label>
                    <input type="password" v-model="passwordInput" required id="password" name="password" />
                </div>
                <button type="submit" class="flex button-login" ref="loginButton"
                    title="Submit your credentials to login.">
                    <span>Login</span>
                    <i class="fa-regular fa-arrow-right-to-bracket"></i>
                </button>
                <div class="splitter-container flex">
                    <hr>
                    </hr>
                    or
                    <hr>
                    </hr>
                </div>
                <button type="button" class="flex" @click="step = 3"
                    title="Login as a Guest with an Administrator provided PIN.">
                    <span>Guest PIN</span>
                    <i class="fa-regular fa-id-badge"></i>
                </button>
                <small>Trouble signing in? If you are supposed to be here, you know who to contact.</small>
            </form>
            <form class="flex-col" @submit.prevent="submit2fa" v-else-if="step === 2">
                <div class="flex-col title">
                    <h1>Almost there,</h1>
                    <strong>{{ username }}</strong>
                </div>
                <div class="flex input-container">
                    <label for="verification" v-if="!verificationInput.length" class="flex">
                        2FA PIN<span>*</span>
                    </label>
                    <input type="text" v-model="verificationInput" required id="verification" name="verification"
                        minlength="6" maxlength="6" />
                </div>
                <div style="height: 41px;"></div>
                <button type="submit" class="flex button-login" ref="verificationButton"
                    title="Submit your 2FA code to complete login.">
                    <span>Submit</span>
                    <i class="fa-regular fa-arrow-right-to-bracket"></i>
                </button>
                <button type="button" class="flex" @click="step = 1" title="Go back to the previous step.">
                    <span>Not you?</span>
                    <i class="fa-regular fa-arrow-left"></i>
                </button>
                <small>Trouble signing in? If you are supposed to be here, you know who to contact.</small>
            </form>
            <form class="flex-col" @submit.prevent="submitGuest" v-else>
                <div class="flex-col title">
                    <h1>Welcome,</h1>
                    <strong>Guest</strong>
                </div>
                <div class="flex input-container">
                    <label for="guest" v-if="!guestInput.length" class="flex">
                        Guest PIN<span>*</span>
                    </label>
                    <input type="text" v-model="guestInput" required id="guest" name="guest" minlength="32"
                        maxlength="32" />
                </div>
                <div style="height: 41px;"></div>
                <button type="submit" class="flex button-login" ref="guestButton"
                    title="Submit your Guest PIN to login.">
                    <span>Login</span>
                    <i class="fa-regular fa-arrow-right-to-bracket"></i>
                </button>
                <button type="button" class="flex" @click="step = 1" title="Go back to the previous step.">
                    <span>Go back</span>
                    <i class="fa-regular fa-arrow-left"></i>
                </button>
                <small>Trouble signing in? If you are supposed to be here, you know who to contact.</small>
            </form>
        </div>
    </main>
</template>

<style scoped>
main {
    box-sizing: border-box;
    padding: 50px;
    height: 100vh;
}

.content-container {
    gap: 50px;
    width: 100%;
    height: 100%;
    max-height: 800px;
}

.background-image,
form {
    border-radius: 25px;
    height: 100%;
}

.background-image-container {
    position: relative;
    height: 100%;
    flex-grow: 1;
}

.background-image-container h1 {
    position: absolute;
    bottom: 25px;
    left: 25px;
    font-weight: bold;
    color: var(--color-background);
}

.background-image {
    background: url(/mesh_2.png) no-repeat center;
    background-size: 130%;
    width: 100%;
}

form {
    box-sizing: border-box;
    padding: 50px;
    width: 400px;
    background-color: var(--color-fill);
    gap: 25px;
}

.title {
    margin-bottom: 75px;
}

.title strong {
    color: var(--color-accent);
    font-size: large;
}

.input-container {
    position: relative;
    width: 100%;
}

.input-container label {
    position: absolute;
    left: 15px;
    pointer-events: none;
    align-items: center;
    gap: 5px;
    color: var(--color-text-light);
}

.input-container label span {
    color: var(--color-accent);
}

.input-container input {
    border: 1px solid var(--color-border-dark);
}

.input-container input,
button {
    width: 100%;
    border-radius: var(--border-radius-mid);
    padding: 10px 15px;
}

button {
    background-color: var(--color-border-dark);
}

button i {
    margin-left: auto;
}

button:hover i {
    margin-right: 5px;
}

.button-login {
    background-color: var(--color-accent);
}

.button-login i,
.button-login span {
    color: var(--color-background);
}

.splitter-container {
    width: 100%;
    justify-content: center;
}

.splitter-container hr {
    width: 20%;
    border: 0;
    border-top: 1px solid var(--color-border-dark);
    margin: 0 30px;
    background-color: transparent;
}

form > small {
    margin-top: auto;
    color: var(--color-text-light);
    opacity: 0.4;
}

@media (width <=1545px) {
    .background-image {
        background-size: 1300px;
        max-width: 1000px;
    }
}

@media (width <=800px) {
    main {
        padding: 25px;
    }

    .content-container {
        justify-content: center;
    }

    .title {
        margin-bottom: 15px;
    }

    .background-image-container {
        display: none;
    }
}
</style>