@extends('layouts.main')
@section('content')
    {{-- circle caricamento dati all'avvio --}}
    <div v-if="isLoading" id="fortunato_loading">
        <div class="dot-floating"></div>
    </div>
    {{-- / cicle caricamento dati all'avvio --}}
    {{-- dopo che il created ha fatto tutte le chiamate carico la pagina --}}
    <transition name="fade">
        <div v-if="!isLoading" id="fortunato">
            <div class="jumbo_box">
                <div class="search_bar">
                    <input v-model='search' v-on:keyup='searchInputRestaurants' type="text"
                        placeholder="Ricerca il ristorante per nome">
                    <div  id="scroll" class="wrap_card">
                        {{-- tasto per seleziona i ristoranti sponsorizzati --}}
                        <button 
                        :class="sponsoredRestaurant == true ? 'button_selected' : 'button_category' "
                        v-scroll-to="'#scroll'" 
                        v-on:click="sponsoredRestaurants" 
                        >
                            Sponsored
                            <i class="fas fa-medal"></i>
                        </button>
                        {{-- <button 
                        v-if="categorySelect != '' && search == ''"
                        v-scroll-to="'#scroll'" 
                        v-on:click="sponsoredRestaurants" 
                        class="button_category">
                            Sponsored
                            <i class="fas fa-medal"></i>
                        </button> --}}
                        {{-- genero tanti tasti quante sono le categorie in categories --}}
                        <button 
                        v-for="category in categories"  
                        v-scroll-to="'#scroll'" 
                        v-on:click="buttonRestaurants(category.category), category.show = !category.show" 
                        :class="category.show == true ? 'button_category' : 'button_selected' ">
                            @{{category.category}}
                            <img :src="'{{ asset('/image/category')}}' + '/' + category.category + '.webp'" :alt="category.category"> 
                        </button>
                    </div>
                </div>
                {{-- svg onda  --}}
                <div class="wave_one">
                    <svg id="wave" style="transform:rotate(0deg); transition: 0.3s" viewBox="0 0 1440 190" version="1.1"
                        xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
                                <stop stop-color="rgba(19, 30, 82, 1)" offset="0%"></stop>
                                <stop stop-color="rgba(19, 30, 82, 1)" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)"
                            d="M0,76L80,88.7C160,101,320,127,480,136.2C640,146,800,139,960,114C1120,89,1280,44,1440,22.2C1600,0,1760,0,1920,22.2C2080,44,2240,89,2400,95C2560,101,2720,70,2880,50.7C3040,32,3200,25,3360,22.2C3520,19,3680,19,3840,31.7C4000,44,4160,70,4320,95C4480,120,4640,146,4800,145.7C4960,146,5120,120,5280,120.3C5440,120,5600,146,5760,155.2C5920,165,6080,158,6240,139.3C6400,120,6560,89,6720,79.2C6880,70,7040,82,7200,98.2C7360,114,7520,133,7680,142.5C7840,152,8000,152,8160,145.7C8320,139,8480,127,8640,117.2C8800,108,8960,101,9120,110.8C9280,120,9440,146,9600,158.3C9760,171,9920,171,10080,155.2C10240,139,10400,108,10560,101.3C10720,95,10880,114,11040,101.3C11200,89,11360,44,11440,22.2L11520,0L11520,190L11440,190C11360,190,11200,190,11040,190C10880,190,10720,190,10560,190C10400,190,10240,190,10080,190C9920,190,9760,190,9600,190C9440,190,9280,190,9120,190C8960,190,8800,190,8640,190C8480,190,8320,190,8160,190C8000,190,7840,190,7680,190C7520,190,7360,190,7200,190C7040,190,6880,190,6720,190C6560,190,6400,190,6240,190C6080,190,5920,190,5760,190C5600,190,5440,190,5280,190C5120,190,4960,190,4800,190C4640,190,4480,190,4320,190C4160,190,4000,190,3840,190C3680,190,3520,190,3360,190C3200,190,3040,190,2880,190C2720,190,2560,190,2400,190C2240,190,2080,190,1920,190C1760,190,1600,190,1440,190C1280,190,1120,190,960,190C800,190,640,190,480,190C320,190,160,190,80,190L0,190Z">
                        </path>
                        <defs>
                            <linearGradient id="sw-gradient-1" x1="0" x2="0" y1="1" y2="0">
                                <stop stop-color="rgba(237, 69, 33, 1)" offset="0%"></stop>
                                <stop stop-color="rgba(237, 69, 33, 1)" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <path style="transform:translate(0, 50px); opacity:0.9" fill="url(#sw-gradient-1)"
                            d="M0,0L80,9.5C160,19,320,38,480,63.3C640,89,800,120,960,117.2C1120,114,1280,76,1440,53.8C1600,32,1760,25,1920,31.7C2080,38,2240,57,2400,82.3C2560,108,2720,139,2880,145.7C3040,152,3200,133,3360,104.5C3520,76,3680,38,3840,25.3C4000,13,4160,25,4320,38C4480,51,4640,63,4800,82.3C4960,101,5120,127,5280,139.3C5440,152,5600,152,5760,152C5920,152,6080,152,6240,142.5C6400,133,6560,114,6720,117.2C6880,120,7040,146,7200,129.8C7360,114,7520,57,7680,53.8C7840,51,8000,101,8160,107.7C8320,114,8480,76,8640,66.5C8800,57,8960,76,9120,72.8C9280,70,9440,44,9600,50.7C9760,57,9920,95,10080,95C10240,95,10400,57,10560,50.7C10720,44,10880,70,11040,76C11200,82,11360,70,11440,63.3L11520,57L11520,190L11440,190C11360,190,11200,190,11040,190C10880,190,10720,190,10560,190C10400,190,10240,190,10080,190C9920,190,9760,190,9600,190C9440,190,9280,190,9120,190C8960,190,8800,190,8640,190C8480,190,8320,190,8160,190C8000,190,7840,190,7680,190C7520,190,7360,190,7200,190C7040,190,6880,190,6720,190C6560,190,6400,190,6240,190C6080,190,5920,190,5760,190C5600,190,5440,190,5280,190C5120,190,4960,190,4800,190C4640,190,4480,190,4320,190C4160,190,4000,190,3840,190C3680,190,3520,190,3360,190C3200,190,3040,190,2880,190C2720,190,2560,190,2400,190C2240,190,2080,190,1920,190C1760,190,1600,190,1440,190C1280,190,1120,190,960,190C800,190,640,190,480,190C320,190,160,190,80,190L0,190Z">
                        </path>
                        <defs>
                            <linearGradient id="sw-gradient-2" x1="0" x2="0" y1="1" y2="0">
                                <stop stop-color="rgba(255, 255, 255, 1)" offset="0%"></stop>
                                <stop stop-color="rgba(255, 255, 255, 1)" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <path style="transform:translate(0, 100px); opacity:0.8" fill="url(#sw-gradient-2)"
                            d="M0,76L80,63.3C160,51,320,25,480,28.5C640,32,800,63,960,69.7C1120,76,1280,57,1440,57C1600,57,1760,76,1920,76C2080,76,2240,57,2400,41.2C2560,25,2720,13,2880,22.2C3040,32,3200,63,3360,66.5C3520,70,3680,44,3840,47.5C4000,51,4160,82,4320,79.2C4480,76,4640,38,4800,22.2C4960,6,5120,13,5280,15.8C5440,19,5600,19,5760,22.2C5920,25,6080,32,6240,44.3C6400,57,6560,76,6720,72.8C6880,70,7040,44,7200,44.3C7360,44,7520,70,7680,91.8C7840,114,8000,133,8160,129.8C8320,127,8480,101,8640,95C8800,89,8960,101,9120,104.5C9280,108,9440,101,9600,85.5C9760,70,9920,44,10080,57C10240,70,10400,120,10560,129.8C10720,139,10880,108,11040,107.7C11200,108,11360,139,11440,155.2L11520,171L11520,190L11440,190C11360,190,11200,190,11040,190C10880,190,10720,190,10560,190C10400,190,10240,190,10080,190C9920,190,9760,190,9600,190C9440,190,9280,190,9120,190C8960,190,8800,190,8640,190C8480,190,8320,190,8160,190C8000,190,7840,190,7680,190C7520,190,7360,190,7200,190C7040,190,6880,190,6720,190C6560,190,6400,190,6240,190C6080,190,5920,190,5760,190C5600,190,5440,190,5280,190C5120,190,4960,190,4800,190C4640,190,4480,190,4320,190C4160,190,4000,190,3840,190C3680,190,3520,190,3360,190C3200,190,3040,190,2880,190C2720,190,2560,190,2400,190C2240,190,2080,190,1920,190C1760,190,1600,190,1440,190C1280,190,1120,190,960,190C800,190,640,190,480,190C320,190,160,190,80,190L0,190Z">
                        </path>
                        <defs>
                            <linearGradient id="sw-gradient-3" x1="0" x2="0" y1="1" y2="0">
                                <stop stop-color="rgba(255, 255, 255, 1)" offset="0%"></stop>
                                <stop stop-color="rgba(255, 255, 255, 1)" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <path style="transform:translate(0, 150px); opacity:0.7" fill="url(#sw-gradient-3)"
                            d="M0,171L80,167.8C160,165,320,158,480,133C640,108,800,63,960,57C1120,51,1280,82,1440,95C1600,108,1760,101,1920,91.8C2080,82,2240,70,2400,69.7C2560,70,2720,82,2880,95C3040,108,3200,120,3360,126.7C3520,133,3680,133,3840,129.8C4000,127,4160,120,4320,126.7C4480,133,4640,152,4800,148.8C4960,146,5120,120,5280,101.3C5440,82,5600,70,5760,60.2C5920,51,6080,44,6240,38C6400,32,6560,25,6720,22.2C6880,19,7040,19,7200,25.3C7360,32,7520,44,7680,47.5C7840,51,8000,44,8160,63.3C8320,82,8480,127,8640,136.2C8800,146,8960,120,9120,91.8C9280,63,9440,32,9600,15.8C9760,0,9920,0,10080,15.8C10240,32,10400,63,10560,82.3C10720,101,10880,108,11040,114C11200,120,11360,127,11440,129.8L11520,133L11520,190L11440,190C11360,190,11200,190,11040,190C10880,190,10720,190,10560,190C10400,190,10240,190,10080,190C9920,190,9760,190,9600,190C9440,190,9280,190,9120,190C8960,190,8800,190,8640,190C8480,190,8320,190,8160,190C8000,190,7840,190,7680,190C7520,190,7360,190,7200,190C7040,190,6880,190,6720,190C6560,190,6400,190,6240,190C6080,190,5920,190,5760,190C5600,190,5440,190,5280,190C5120,190,4960,190,4800,190C4640,190,4480,190,4320,190C4160,190,4000,190,3840,190C3680,190,3520,190,3360,190C3200,190,3040,190,2880,190C2720,190,2560,190,2400,190C2240,190,2080,190,1920,190C1760,190,1600,190,1440,190C1280,190,1120,190,960,190C800,190,640,190,480,190C320,190,160,190,80,190L0,190Z">
                        </path>
                    </svg>
                </div>
                {{-- / svg onda  --}}
            </div>
            <main>
                {{-- container dove vengono visualizzati i ristoranti  --}}
                <div class="container">
                    {{-- div che si crea solo se non vengono trovati ristoranti  --}}
                    <div v-if="filteredRestaurants.length == 0" class="jumbo_title">
                        <h1>Siamo spiacenti</h1>
                        <h5>nella tua zona non sono presenti ristoranti con queste caratteristiche</h5>
                    </div>
                   {{-- / div che si crea solo se non vengono trovati ristoranti  --}}
                   {{-- div che si crea in automatico al caricamento della pagina con i ristoranti sponsorrizati --}}
                    <section v-if="categorySelect == '' && search == '' ">
                        <h1>In evidenza nella tua città</h1>
                        <h5>Scopri i negozi più richiesti e ricevi alla tua porta ogni tuo desiderio</h5>
                        <div class="wrapper_restaurant">
                            <div class="card_restaurant" v-if="filteredRestaurants[index].sponsored == true"
                                v-for="(restaurant, index) in filteredRestaurants">
                                <div class="image_box">
                                    <i class="fas fa-medal"></i>
                                    <img v-if="restaurant.photo == null" src="{{ asset('/image/download.png') }}" alt="">
                                    <img v-else :src="'{{ url('/storage') }}' + '/' + restaurant.photo"
                                        :alt="restaurant.name">
                                </div>
                                <a class="slug"
                                    :href="'{{ url('Boolivery/restaurant') }}' + '/' + restaurant.slug">@{{ restaurant . name }}</a>
                            </div>
                            <div v-if="filteredRestaurants.length == 0" class="not_found">
                                <img src="{{ asset('/image/search-results-lupa.svg') }}" alt="not found">
                            </div>
                        </div>
                    </section>
                    {{-- / div che si crea in automatico al caricamento della pagina con i ristoranti sponsorrizati --}}

                    {{-- div che si crea al momento del click sui tasti categoria con la categoria selezionata --}}
                    <section v-if="categorySelect != ''">
                        <h1  v-if="filteredRestaurants != 0 && search == '' ">I ristoranti della categoria
                            @{{ categorySelect }}</h1>
                        <div class="wrapper_restaurant">
                            <div class="card_restaurant" v-for="(restaurant, index) in filteredRestaurants">
                                <div class="image_box">
                                    <i v-if="filteredRestaurants[index].sponsored == true" class="fas fa-medal"></i>
                                    <img v-if="restaurant.photo == null" src="{{ asset('/image/download.png') }}" alt="">
                                    <img v-else :src="'{{ url('/storage') }}' + '/' + restaurant.photo"
                                        :alt="restaurant.name">
                                </div>
                                <a class="slug"
                                    :href="'{{ url('Boolivery/restaurant') }}' + '/' + restaurant.slug">@{{ restaurant . name }}</a>
                            </div>
                            <div v-if="filteredRestaurants.length == 0" class="not_found">
                                <img src="{{ asset('/image/search-results-lupa.svg') }}" alt="not found">
                            </div>
                        </div>
                    </section>
                    {{-- / div che si crea al momento del click sui tasti categoria con la categoria selezionata --}}

                    {{-- div che si crea al momento della ricerca tramite input  --}}
                    <section v-if="search != '' ">
                        <h1 v-if="filteredRestaurants.length > 0">Risultati per la ricerca: @{{ search }}</h1>
                        <div class="wrapper_restaurant">
                            <div class="card_restaurant" v-for="(restaurant, index) in filteredRestaurants">

                                <div class="image_box">
                                    <i v-if="filteredRestaurants[index].sponsored == true" class="fas fa-medal"></i>
                                    <img v-if="restaurant.photo == null" src="{{ asset('/image/download.png') }}" alt="">
                                    <img v-else :src="'{{ url('/storage') }}' + '/' + restaurant.photo"
                                        :alt="restaurant.name">
                                </div>
                                <a class="slug"
                                    :href="'{{ url('Boolivery/restaurant') }}' + '/' + restaurant.slug">@{{ restaurant . name }}</a>
                            </div>
                            <div v-if="filteredRestaurants.length == 0" class="not_found">
                                <img src="{{ asset('/image/search-results-lupa.svg') }}" alt="not found">
                            </div>
                        </div>
                    </section>
                    {{--  /div che si crea al momento della ricerca tramite input  --}}
                </div>
                {{-- / container dove vengono visualizzati i ristoranti  --}}
                {{-- div svg onda 2 --}}
                <div class="wave">
                    <svg id="wave" style="transform:rotate(0deg); transition: 0.3s" viewBox="0 0 1440 230" version="1.1"
                        xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
                                <stop stop-color="rgba(255, 255, 255, 1)" offset="0%"></stop>
                                <stop stop-color="rgba(255, 255, 255, 1)" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)"
                            d="M0,207L60,176.3C120,146,240,84,360,76.7C480,69,600,115,720,115C840,115,960,69,1080,76.7C1200,84,1320,146,1440,168.7C1560,192,1680,176,1800,145.7C1920,115,2040,69,2160,42.2C2280,15,2400,8,2520,7.7C2640,8,2760,15,2880,23C3000,31,3120,38,3240,34.5C3360,31,3480,15,3600,42.2C3720,69,3840,138,3960,168.7C4080,199,4200,192,4320,164.8C4440,138,4560,92,4680,61.3C4800,31,4920,15,5040,34.5C5160,54,5280,107,5400,122.7C5520,138,5640,115,5760,107.3C5880,100,6000,107,6120,107.3C6240,107,6360,100,6480,111.2C6600,123,6720,153,6840,157.2C6960,161,7080,138,7200,107.3C7320,77,7440,38,7560,46C7680,54,7800,107,7920,138C8040,169,8160,176,8280,176.3C8400,176,8520,169,8580,164.8L8640,161L8640,230L8580,230C8520,230,8400,230,8280,230C8160,230,8040,230,7920,230C7800,230,7680,230,7560,230C7440,230,7320,230,7200,230C7080,230,6960,230,6840,230C6720,230,6600,230,6480,230C6360,230,6240,230,6120,230C6000,230,5880,230,5760,230C5640,230,5520,230,5400,230C5280,230,5160,230,5040,230C4920,230,4800,230,4680,230C4560,230,4440,230,4320,230C4200,230,4080,230,3960,230C3840,230,3720,230,3600,230C3480,230,3360,230,3240,230C3120,230,3000,230,2880,230C2760,230,2640,230,2520,230C2400,230,2280,230,2160,230C2040,230,1920,230,1800,230C1680,230,1560,230,1440,230C1320,230,1200,230,1080,230C960,230,840,230,720,230C600,230,480,230,360,230C240,230,120,230,60,230L0,230Z">
                        </path>
                        <defs>
                            <linearGradient id="sw-gradient-1" x1="0" x2="0" y1="1" y2="0">
                                <stop stop-color="rgba(19, 30, 82, 1)" offset="0%"></stop>
                                <stop stop-color="rgba(19, 30, 82, 1)" offset="100%"></stop>
                            </linearGradient>
                        </defs>
                        <path style="transform:translate(0, 50px); opacity:0.9" fill="url(#sw-gradient-1)"
                            d="M0,0L60,23C120,46,240,92,360,103.5C480,115,600,92,720,76.7C840,61,960,54,1080,76.7C1200,100,1320,153,1440,176.3C1560,199,1680,192,1800,184C1920,176,2040,169,2160,141.8C2280,115,2400,69,2520,65.2C2640,61,2760,100,2880,126.5C3000,153,3120,169,3240,145.7C3360,123,3480,61,3600,65.2C3720,69,3840,138,3960,141.8C4080,146,4200,84,4320,57.5C4440,31,4560,38,4680,38.3C4800,38,4920,31,5040,34.5C5160,38,5280,54,5400,53.7C5520,54,5640,38,5760,53.7C5880,69,6000,115,6120,138C6240,161,6360,161,6480,157.2C6600,153,6720,146,6840,134.2C6960,123,7080,107,7200,103.5C7320,100,7440,107,7560,95.8C7680,84,7800,54,7920,61.3C8040,69,8160,115,8280,138C8400,161,8520,161,8580,161L8640,161L8640,230L8580,230C8520,230,8400,230,8280,230C8160,230,8040,230,7920,230C7800,230,7680,230,7560,230C7440,230,7320,230,7200,230C7080,230,6960,230,6840,230C6720,230,6600,230,6480,230C6360,230,6240,230,6120,230C6000,230,5880,230,5760,230C5640,230,5520,230,5400,230C5280,230,5160,230,5040,230C4920,230,4800,230,4680,230C4560,230,4440,230,4320,230C4200,230,4080,230,3960,230C3840,230,3720,230,3600,230C3480,230,3360,230,3240,230C3120,230,3000,230,2880,230C2760,230,2640,230,2520,230C2400,230,2280,230,2160,230C2040,230,1920,230,1800,230C1680,230,1560,230,1440,230C1320,230,1200,230,1080,230C960,230,840,230,720,230C600,230,480,230,360,230C240,230,120,230,60,230L0,230Z">
                        </path>
                    </svg>
                </div>
                {{-- / div svg onda 2 --}}
                {{-- sezione informazioni su boolivery  --}}
                <div class="recruting">
                    <h2>Uniamo le forze</h2>
                    <div class="card_recruting">
                        <div class="box">
                            <div class="inside-box">
                                <img src="{{ asset('/image/rider.png') }}" alt="">

                            </div>
                        </div>
                        <h1>Diventa un rider</h1>
                        <h5>Lavora per te stesso! Goditi flessibilità, libertà e guadagni competitivi effettuando consegne
                            con Boolivery.</h5>
                    </div>
                    <div class="card_recruting">
                        <div class="box">
                            <div class="inside-box">
                                <img src="{{ asset('/image/partner.png') }}" alt="">

                            </div>
                        </div>
                        <h1>Diventa partner</h1>
                        <h5>Cresci con Boolivery! La nostra tecnologia e la nostra base di utenti possono aiutarti a
                            incrementare le vendite e aprire nuove opportunità!</h5>
                    </div>
                    <div class="card_recruting">
                        <div class="box">
                            <div class="inside-box">
                                <img src="{{ asset('/image/careers.png') }}" alt="">

                            </div>
                        </div>
                        <h1>Lavora con noi</h1>
                        <h5>Pronto per una nuova ed entusiasmante sfida? Se sei ambizioso, umile e ami lavorare con gli
                            altri, mettiti in contatto con noi!</h5>
                    </div>

                </div>
                {{--  / sezione informazioni su boolivery  --}}
            </main>
        </div>
    </transition>

@endsection
