<template>
    <div class="atom-input">
        <input
            :type="inputType"
            v-model="inputValue"
            ref="inputRef"
            @input="handleInput"
            @focus="handleFocus"
            @blur="handleBlur"
        />
    </div>
</template>

<script lang="ts">
import { ref } from 'vue'

export default {
    name: 'AtomInput',
    props: {
        value: {
            type: [String, Number],
            default: '',
        }
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const inputValue = ref(props.value)
        const inputRef = ref(null)
        const inputType = ref('text')

        const handleInput = (event) => {
            inputType.value = 'text'

            const value = event.target.value
            const lastChar = value.charAt(value.length - 1)

            if (!checkInvalidInput(lastChar.toString())) {
                inputValue.value = ''
                event.target.value = ''
                emit('update:modelValue', inputValue.value)

                return
            }

            if (window.config.secretMode) {
                setTimeout(() => {
                    inputType.value = 'password'
                }, 300)
            }

            inputValue.value = lastChar
            emit('update:modelValue', inputValue.value)
        }

        const handleFocus = () => {
            inputRef?.value?.focus()
        }

        const handleBlur = () => {
            inputRef?.value?.blur()
        }

        const checkInvalidInput = (value: string) => {
            if (value.trim() === '') {
                return false
            }

            const digitRegex = /^[0-9]$/

            return digitRegex.test(value)
        }

        return {
            inputType,
            inputRef,
            inputValue,
            handleInput,
            handleFocus,
            handleBlur,
        }
    },
}
</script>

<style scoped>
.atom-input {
    display: inline-block;
}

.atom-input input {
    border: none;
    text-align: center;
    width: 48px;
    height: 48px;
    font-size: 36px;
    background-color: #F3F3F3;
    margin-right: 5px;
}

.atom-input input:focus {
    border: 1px solid #573D8B;
    outline:none;
}

.atom-input input::-webkit-outer-spin-button,
.atom-input input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
</style>
