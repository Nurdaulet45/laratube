<template>
    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label >Техник: </label>

                        <select v-model="techID"  class="form-control select2">
                            <option selected disabled value>Выберите техника</option>
                            <option value="all">Все</option>
                            <option :value="tech.IDTech" v-for="(tech, index) in technics" :key="index">{{ tech.FIO}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <VueCtkDateTimePicker
                            class="date"
                            id="start"
                            :format="'DD.MM.YYYY'"
                            :inputSize="'lg'"
                            :no-label="false"
                            label="Начало"
                            :onlyDate="true"
                            formatted="ll"
                            :noButtonNow="true"
                            v-model="startDate"
                        />
                    </div>
                </div>
                <div class="col-md-4 ">
                    <div class="form-group">
                        <VueCtkDateTimePicker
                            class="date"
                            id="end"
                            :format="'DD.MM.YYYY'"
                            :inputSize="'lg'"
                            :no-label="false"
                            label="Конец"
                            :onlyDate="true"
                            formatted="ll"
                            :noButtonNow="true"
                            v-model="endDate"
                        />
                    </div>
                </div>
                <div class="col-md-2">
                    <button @click.prevent="tech()" class="btn btn-primary">Найти</button>
                </div>
            </div>
            <div class="row">
                <div class="form-group container1">

                    <li class="pt">
                        <i class="fa fa-circle-o " style="color: #1d97ff"
                        ></i>
                        <p class="mlmt">Днем с 07 до 20</p>
                    </li>
                    <li class="pt">
                        <i class="fa fa-circle-o " style="color: red"
                        ></i>
                        <p class="mlmt">Ночью от 20:00 до 07:00</p>
                    </li>
                    <li class="pt">
                        <i class="fa fa-circle-o " style="color: yellow"></i>
                        <p class="mlmt">Выходные</p>
                    </li>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div id="map">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <codeSql :sql="`
                <h4>Техники:</h4>
                select
                    IDTech, FIO
                from
                    AESBASES..technic
                <h4>Техник Map:</h4>
                select t.IDTech,
                    t.FIO,
                    o.InTime, o.OutTime, o.Zones,
                    p.Info,
                    c.OTIS_NUMBER, c.ORG, c.[NAME], c.ADR, c.Xk, c.Yk
                from
                    AESBASES..technic t (nolock)
                    join AESBASES..otchet o (nolock) on o.IDTech = t.IDTech and o.InTime between '` + sql.startDate + `' and '` + sql.endDate + `'
                    join AESBASES..places p (nolock) on p.IDOtis = o.IDOtis
                    left  join MapInfo1..clients c (nolock) on c.ORGID = p.OrgID and c.OTIS_NUMBER = p.Otis and c.ISPROTECTED = 1
                where
                    '`+sql.techID + `'
                    c.Xk is not null
                order by
                    p.Otis


                <h4>Заявки техников таблица:</h4>

                select  COUNT(1) as cnt, t.IDTech,
                    t.FIO,
                        SUM(CASE WHEN (datepart(hh, o.InTime) >= 7 AND (datepart(hh, o.InTime) < 20 OR
                        datepart(hh, o.InTime) = 20 AND datepart(mi, o.InTime) = 0)) THEN 1 ELSE 0 END) AS [Den],
                    SUM(CASE WHEN ((datepart(hh,  o.InTime) > 20 OR
                        datepart(hh,  o.InTime) = 20 AND datepart(mi, o.InTime) >= 1) OR datepart(hh,  o.InTime) < 7) THEN 1 ELSE 0 END) AS [Noch],
                    sum(CASE WHEN datepart(dw, o.InTime) in (6,7) THEN 1 ELSE 0 END) as [Vihodnie]
                from
                    AESBASES..technic t (nolock)
                    join AESBASES..otchet o (nolock) on o.IDTech = t.IDTech and o.InTime between '` + sql.startDate + `' and '` + sql.endDate + `'
                    join AESBASES..places p (nolock) on p.IDOtis = o.IDOtis
                    left join MapInfo1..clients c (nolock) on c.ORGID = p.OrgID and c.OTIS_NUMBER = p.Otis and c.ISPROTECTED = 1
                where
                    c.Xk is not null
                    group by t.IDTech,
                    t.FIO
                    order by t.FIO asc
                `"/>
                </div>

            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12 col-lg-6">
                    <h2>
                        Заявки техников
                        <span style="font-size:14px"
                        >С {{ startDate }} до {{ endDate }} - <b> {{ cnt }} </b>  заявок</span
                        >
                    </h2>
                    <div class="table-responsive">
                        <table class="table  table-bordered table-hover">
                            <thead class="thead-light">
                            <tr role="row">
                                <th>Техник</th>
                                <th>Общее количество</th>
                                <th>Днем</th>
                                <th>Ночью</th>
                                <th>Выходные дни</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                role="row" v-for="(value , index) in qtyOrders" :key="'techOrder'+index" @click.prevent="tech(value.IDTech)">
                                <td>{{value.FIO}}</td>
                                <td>{{value.cnt}}</td>
                                <td>{{value.Den}}</td>
                                <td>{{value.Noch}}</td>
                                <td>{{value.Vihodnie}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueCtkDateTimePicker from "vue-ctk-date-time-picker";
    import "vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css";
    import codeSql from "../plugins/CodeSql";

    export default {
        name: "tech_orders_map",
        components: {
            VueCtkDateTimePicker: VueCtkDateTimePicker,
            codeSql
        },
        data(){
            return{
                startDate: '',
                endDate: '',
                techID: '',
                technics: '',
                qtyOrders: [],
                cnt:  '',
                map_obj: {
                    myMap: '',
                    clusterer: ''
                }
            }
        },
        methods:{
            map(){
                ymaps.ready(this.init)
            },
            init(){
                this.map_obj.myMap = new ymaps.Map('map', {center:[43.2199387972459, 76.8524551391602], zoom: 9}, {searchControlProvider: 'yandex#search'})
                var customItemContentLayout = ymaps.templateLayoutFactory.createClass(
                    // Флаг "raw" означает, что данные вставляют "как есть" без экранирования html.
                    "<h3 class=ballon_header>{{ properties.balloonContentHeader|raw }}</h3>" +
                    "<div class=ballon_body>{{ properties.balloonContentBody|raw }}</div>" +
                    "<div class=ballon_footer>{{ properties.balloonContentFooter|raw }}</div>"
                );

                this.map_obj.clusterer = new ymaps.Clusterer({
                    // Макет метки кластера pieChart.
                    clusterIconLayout: "default#pieChart",
                    // Радиус диаграммы в пикселях.
                    clusterIconPieChartRadius: 26,
                    // Радиус центральной части макета.
                    clusterIconPieChartCoreRadius: 15,
                    // Ширина линий-разделителей секторов и внешней обводки диаграммы.
                    clusterIconPieChartStrokeWidth: 3,
                    // Определяет наличие поля balloon.
                    hasBalloon: true,
                    clusterBalloonContentLayout: "cluster#balloonAccordion",
                    clusterBalloonItemContentLayout: customItemContentLayout,
                });
                this.map_obj.myMap.geoObjects.add(this.map_obj.clusterer);
            },
            techList(){
                axios.get('/api/map/tech-orders/technics').then(res=> {
                    this.technics = Object.values(res.data)
                })
            },
            tech(IDTech = false){
                if (IDTech !== false){
                    this.techID = IDTech;
                    window.scrollTo(0, 152);
                }

                let params = {
                    ID: this.techID,
                    from: this.startDate,
                    to: this.endDate
                };
                this.$router.push({query: params});

                this.qtyOrdersTech();

                if (this.techID) {
                    let loader = this.$loading.show({
                        // Optional parameters
                        container: this.fullPage ? null : this.$refs.formContainer,
                        canCancel: false,
                        onCancel: this.onCancel
                    });
                    axios.get('/api/map/tech-orders/technic/' + this.techID,{
                            params:{
                                start: this.startDate,
                                end: this.endDate
                            }
                        }
                    ).then(res=>{

                        loader.hide();
                        let data = res.data;
                        let len = Object.values(data).length;
                        if(len === 0){
                            this.$swalError(
                                "Не найден",
                                "Заявки"
                            );
                        } else {
                            data = Object.values(data);
                            this.createCluster(data);
                        }
                    }).catch(err =>{
                        loader.hide();
                        this.$swalError(
                            "Ошибка сервера! Сообщите об этой проблеме администратором сайта",
                            "Ошибка"
                        );
                    })
                } else {
                    this.$swalError('Не Выбран', 'Техник')
                }

            },
            createCluster(object){
                let myPlacemark = [];
                let len = object.length;
                for(let i=0; i <len; i++){
                    myPlacemark[i] = new ymaps.Placemark(
                        [
                            object[i].Yk,
                            object[i].Xk
                        ],
                        {
                            balloonContentBody:
                                '<b>Организация: </b>'+ object[i].ORG + '</br>' +
                                '<b>Номер Отиса: </b>'+ object[i].OTIS_NUMBER + '</br>' +
                                '<b>ФИО: </b>' + object[i].FIO + '</br>' +
                                '<b>Объект: </b>' + object[i].NAME + '</br>' +
                                '<b>Инфо: </b>' + object[i].Info + '</br>' +
                                '<b>Адрес: </b>' + object[i].ADR + '</br>' +
                                '<b>Прибыл: </b>' + object[i].InTime + '</br>' +
                                '<b>Убыл: </b>' + object[i].OutTime + '</br>' +
                                '<b>Причина: </b>' + object[i].Zones + '</br>',
                            clusterCaption: object[i].NAME,
                            hintContent:
                                '<b>Объект: </b>' + object[i].NAME +'</br>'+
                                '<b>Прибыл: </b>' + object[i].OutTime + '</br>',
                        },
                        {
                            preset: 'islands#' + object[i].color+'Icon'
                        }
                    );
                }
                this.map_obj.clusterer.removeAll();
                this.map_obj.clusterer.add(myPlacemark);
                this.map_obj.myMap.setBounds(this.map_obj.clusterer.getBounds());
                this.map_obj.myMap.geoObjects.add(this.map_obj.clusterer);


                // this.map_obj.myMap.setCenter([object[0].Yk, object[0].Xk], 12, {
                //     checkZoomRange: true
                // });
            },
            date(){
                let date = new Date();
                let day = date.getDate().toString();
                let month = date.getMonth() + 1;

                month = month.toString();
                if (month.length === 1){
                    month = '0' + month
                }
                let year =  date.getFullYear();
                this.endDate = day + '.' + month + '.' + year;
                // this.endDate =
                //     [
                //     date.getDate(),
                //     date.getMonth() + 1,
                //     date.getFullYear(),
                // ].join(".");

                let prevDate = new Date(new Date()-86400000);
                let sDay = prevDate.getDate();
                let sMonth = (prevDate.getMonth() + 1).toString();
                if (sMonth.length === 1){
                    sMonth = '0' + sMonth
                }
                let sYear = prevDate.getFullYear();
                this.startDate = sDay + '.' + sMonth + '.' + sYear
                // this.startDate =  [
                //     prevDay.getDate()-1,
                //     prevDay.getMonth() + 1,
                //     prevDay.getFullYear(),
                // ].join(".");
            },

            qtyOrdersTech(){
                let loader = this.$loading.show({
                    // Optional parameters
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                    onCancel: this.onCancel
                });
                axios.get('/api/map/tech-orders/qty', {
                    params:{
                        start: this.startDate,
                        end: this.endDate
                    }
                }).then(res=> {
                    loader.hide();

                    this.cnt = res.data.cnt;
                    this.qtyOrders = res.data.qty_orders;
                    // this.qtyOrders = res.data;
                }).catch(err=>{
                    loader.hide();
                    this.$swalError(
                        "Ошибка сервера! Сообщите об этой проблеме администратором сайта",
                        "Ошибка"
                    );
                })
            }
        },
        created() {
            this.map();
            this.techList();
            this.date();
            this.qtyOrdersTech()
        },
        computed:{
            sql(){
                let techID = 't.IDTech = 98 and';
                if (this.techID ==='all' || this.techID.length === 0){
                    techID = ''
                } else {
                    techID = "t.IDTech = " + this.techID + " and"
                }

                return {
                    techID: techID ,
                    startDate: this.startDate ? this.startDate : '12/16/2020',
                    endDate:  this.endDate ? this.endDate : '15/16/2020',
                }
            }
        }
    }
</script>

<style scoped>
    #map {
        border: 2px outset #3c8dbc;
        height: 800px;
    }
    .btn{
        min-width: 99px;
        min-height: 48px;
    }
    select{
        cursor: pointer;
        background-color: #fff;
        -webkit-transition-duration: .3s;
        transition-duration: .3s;
        position: relative;
        width: 100%;
        height: 42px;
        min-height: 42px;
        padding-left: 12px;
        padding-right: 44px;
        font-weight: 400;
        /*-webkit-appearance: none;*/
        outline: none;
        border: 1px solid rgba(0,0,0,.2);
        border-radius: 4px;
        font-size: 16px;
        z-index: 0;
    }
    tr{
        cursor: pointer;
    }
    .container1 {
        list-style-type: none;
        float: left;
        width: 100%;
        margin-bottom: 5px;
        margin-top: 10px;
        margin-left: 15px;
    }
    .fa{
        float:left;
        font-size: 24px;
    }
    .pt {
        padding-top: 3px;
        margin: 10px;
        float: left;
    }
    p.mlmt{
        margin-left: 33px;
        margin-top: 3px;
    }
</style>
