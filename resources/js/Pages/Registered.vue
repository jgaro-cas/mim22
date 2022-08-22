<template>
    <div class="container max-w-3xl mx-auto mt-2 d-flex flex-col justify-content-center">
        <img class="img-fluid" :src="bannerUrl" alt="Logo" >
        <h1 class="mb-5 mt-4">Inscription résussie</h1>
        <p>Merci pour ton inscription en tant que bénévole à Festi'Cheyres ! Toutes les informations ont été envoyées à l'adresse : <strong>{{mail}}</strong></p>
        <p>Si l'adresse est erronée, contacte-moi à <a href="mailto:benevoles@festicheyres.ch">benevoles@festicheyres.ch</a></p>
        <Link href="/">Inscrire d'autres bénévoles</Link>
    </div>
</template>

<script>

import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link,
    },

    props: {
        mail : ''
    },

    data(){
        return {
            bannerUrl : ''
        }
    },

    mounted() {
        this.bannerUrl = window.location.origin + '/storage/Festicheyres2022_banner.jpg'
    }
}
</script>

<style>

</style>
