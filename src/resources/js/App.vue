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
    name: 'App',
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

            await window.axios.post(route.value, {
                code: code,
            })
            .then(function (response) {
                const res: object = response.data

                if (res.shouldRedirect) {
                    window.location.replace(res.url)

                    return
                }

                throw new Error('Error')
            })
            .catch(function (error) {
                console.log(error);
            })
        }

        return {
            route,
            handleAfterFilled
        }
    }
}
</script>
