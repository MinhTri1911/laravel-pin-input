<template>
    <div class="pin-code">
        <AtomInput
            v-for="index in pinLength"
            :key="index"
            ref="pinInputs"
            v-model="pinValues[index - 1]"
            :maxlength="1"
            @update:modelValue="(data) => handlePinInput(index - 1, data)"
            @paste="handlePaste($event)"
        />
    </div>
</template>

<script>
import {onMounted, ref} from 'vue'
import AtomInput from './Atom/AtomInput.vue'

export default {
    name: 'PinInput',
    components: {
        AtomInput,
    },
    props: {
        pinLength: {
            type: String,
            default: 4,
        }
    },
    setup(props, { emit }) {
        const pinLength = ref(props.pinLength)
        const pinInputs = ref([])

        // Array to store the PIN values
        const pinValues = ref([])

        onMounted(() => {
            pinInputs.value[0].handleFocus()
            pinLength.value = window.config?.pinLength
            pinValues.value = Array(pinLength.value).fill('')
        })

        const checkValidInput = () => {
            let flag = true

            pinValues.value.forEach((value) => {
                if (value === '') {
                    flag = false
                }
            })

            return flag
        }

        // Handle individual PIN input
        const handlePinInput = (index, data) => {
            const nextInput = pinInputs.value[index + 1]
            const currentInput = pinInputs.value[index]

            if (data.trim() === '') {
                pinValues.value[index] = ''
                currentInput.handleFocus()

                return
            }

            // Focus on the next input
            if (nextInput && pinValues.value[index] !== '') {
                nextInput.handleFocus()
                currentInput.handleBlur()
            }

            // Invoke method after all inputs are filled
            if (checkValidInput() || (index === pinLength.value - 1 && checkValidInput())) {
                invokeMethod()
            }
        }

        // Handle paste event
        const handlePaste = (event) => {
            event.preventDefault()
            const pastedText = event.clipboardData.getData('text/plain')

            if (!/[0-9]$/.test(pastedText)) {
                return
            }

            const digits = pastedText.slice(0, props.pinLength).split('')
            digits.forEach((digit, index) => {
                if (pinInputs.value[index]) {
                    pinInputs.value[index].inputValue  = digit
                    pinValues.value[index] = digit
                    handlePinInput(index, digit)
                }
            })
        }

        // Method to be invoked after all inputs are filled
        const invokeMethod = () => {
            emit('filled:done', pinValues.value)
        }

        return {
            pinLength,
            pinInputs,
            pinValues,
            handlePinInput,
            handlePaste,
        }
    },
}
</script>

<style scoped>

.pin-code {
    padding: 0;
    margin: 10px auto;
    display: flex;
    justify-content:center;
}
</style>
