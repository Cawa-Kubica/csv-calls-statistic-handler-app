<main class="mt-2" id="callsDataImportApp">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-success" role="alert" v-if="isSuccess && Array.isArray(clients_data)">
                    Файл успешно обработан
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="alert alert-danger" role="alert" v-if="isError">
                    {{ errorDescription }}
                </div>

                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" ref="file" accept=".csv" v-on:change="handleFileUpload()"
                               class="custom-file-input" id="file" required>
                        <label class="custom-file-label" for="file">
                            {{ file ? file.name : 'Выберите файл' }}
                        </label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-primary" v-on:click="submitFile()" type="button"
                                :disabled="!validateFileUpload">
                                <span v-if="inProcess" class="spinner-grow spinner-grow-sm" role="status"
                                      aria-hidden="true"></span>
                            <span v-if="!inProcess">Импорт</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5" v-if="inProcess">
            <div class="mx-auto">
                <div class="spinner-grow text-primary" role="status"></div>
                <div class="spinner-grow text-info" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <div class="spinner-grow text-primary" role="status"></div>
            </div>
        </div>

        <hr v-if="isSuccess"/>

        <div class="row" v-if="isSuccess && Array.isArray(clients_data)">
            <div class="col-lg-12">
                <div class="table-responsive" v-if="clients_data.length > 0">
                    <table class="table" id="clients_call_statistic">
                        <thead>
                        <tr>
                            <th scope="col">ID клиента</th>
                            <th scope="col">Статистика по континентам</th>
                            <th scope="col">Страны</th>
                            <th scope="col">Всего звонков</th>
                            <th scope="col">Общая длительность</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr style="width: 50%;" v-for="(client, index) in clients_data">
                            <th scope="row">
                                {{ client.client_id }}
                            </th>
                            <td>
                                <div v-if="client.total_calls_count > 0"
                                     style="border-bottom: 1px solid rgba(0,0,0,.1); padding-bottom: 5px;">
                                    <small>Кол-во звонков:</small>
                                    <br/>
                                    <small v-if="client.calls_count_by_continents"
                                           v-for="(count, continent) in client.calls_count_by_continents">
                                        {{ continent }}: <strong>{{ count }}</strong>
                                    </small>
                                </div>
                                <div v-if="client.calls_total_duration > 0"
                                     style="padding-top: 5px;">
                                    <small>Длительность звонков:</small>
                                    <br/>
                                    <small v-if="client.calls_duration_by_continents"
                                           v-for="(duration, continent) in client.calls_duration_by_continents">
                                        {{ continent }}: <strong>{{ duration }}</strong>
                                    </small>
                                </div>
                            </td>
                            <td style="width: 10%;">
                                <div v-if="client.client_countries">
                                        <span v-for="(country, index) in client.client_countries" style="padding: 5px;">
                                            <img v-if="country.flag" style="max-height: 15px;" :title="country.code"
                                                 :src="country.flag" :alt="country.code">
                                            <small v-if="!country.flag">{{ country.code }}</small>
                                        </span>
                                </div>
                            </td>
                            <td>
                                {{ client.total_calls_count }}
                            </td>
                            <td>
                                {{ client.calls_total_duration }}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div>
                        <button type="button" class="btn btn-success btn-sm"
                                v-on:click="toExcel">
                            В Excel
                        </button>
                        <button type="button" class="btn btn-warning btn-sm"
                                onclick="window.location.reload()">
                            Обновить
                        </button>

                        <small class="float-right">
                            Всего элементов: <strong>{{ clients_data.length }}</strong>
                        </small>
                    </div>
                </div>

                <div class="alert alert-dark" role="alert" v-if="clients_data.length === 0">
                    В файле не найдено данных
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="/js/app.js"></script>