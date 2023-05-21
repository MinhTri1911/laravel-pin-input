<template>
    <div>
        <PinInput
            @filled:done="data => handleAfterFilled(data)"
        />
    </div>
</template>

<script lang="ts">

import PinInput from './components/PinInput.vue'
import { onMounted, ref, isProxy, toRaw } from 'vue'

export default {
    name: 'Test',
    components: {
        PinInput
    },
    setup() {
        const route = ref(null)

        onMounted(() => {
            route.value = window.config?.routes?.post2fa
        })

        const handleAfterFilled = async (data) => {
            let rawData = data

            if (isProxy(rawData)) {
                rawData = toRaw(rawData)
            }

            const code = rawData.join('')

            alert(`Filled with code : ${code}`)
        }

        return {
            route,
            handleAfterFilled
        }
    }
}
</script>
