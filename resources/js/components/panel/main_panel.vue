<template>
    <div>
        <main_navbar></main_navbar>
        <main_content></main_content>
    </div>
</template>

<script>
    import main_navbar from './main_navbar'
    import main_content from './main_content'

    export default {
        name: "main_panel",
        components: {main_navbar, main_content},

    }
</script>

<style scoped>

</style>