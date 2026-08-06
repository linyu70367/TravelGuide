@extends("front.layout")

@section("title")
修改紀錄
@endsection

@push("style")
<style>
    .change-log {
        padding: 24px 0;
    }

    .change-log .log-item {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid #E5E7EB;
    }

    .change-log h1 {
        margin-bottom: 10px;
        color: #1B2E5E;
        font-size: 18px;
        font-weight: 700;
    }

    .change-log p {
        margin-bottom: 6px;
        color: #222222;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.7;
    }
</style>
@endpush

@section("content")

<div class="container">
    <div class="display-5 fw-900 text-center text-bg-info rounded rounded-3 p-3 my-3">相關統計圖表</div>
    <div class="row">
        <div class="card-body col-6" id="app">
            <canvas id="myChart"></canvas>
        </div>
        <div class="card-body col-6" id="viewschartapp">
            <canvas id="viewsChart"></canvas>
        </div>
        <div class="card-body col-6" id="typeschartapp">
            <canvas id="typesChart"></canvas>
        </div>
    </div>
</div>

<div class="row change-log">
    <div class="display-4">修改紀錄</div>
    <div class="col-12 log-item ms-3">
        <h1>7/28</h1>
        <p>修正 views 為卡片，獨立出 views.css</p>
        <p>/views-0 表格</p>
        <p>/views-1 卡片</p>
        <p>/views?region=中部&type=2 最新版，<br>列表頁支援透過 URL query parameters 帶入篩選條件</p>
    </div>

    <div class="col-12 log-item ms-3">
        <h1>7/29</h1>
        <p>完成/views/{id}</p>
        <p>修正 header，獨立出 header.css</p>
        <p>首頁初稿</p>
    </div>

    <div class="col-12 log-item ms-3">
        <h1>8/3</h1>
        <p>完成/travelfood/{id}</p>
        <p>寫api/travelfood</p>
        <p>在travelfood_detail.blade中呼叫api</p>
        <p>用jQuery的ajax</p>
    </div>

    <div class="col-12 log-item ms-3">
        <h1>8/3</h1>
        <p>完成/travelfood</p>
        <p>route取opendata和id:index+1</p>
        <p>用vuejs渲染表格、分區篩選、分頁</p>
    </div>
</div>

<script src="js/vue.global.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const App = {
        data() {
            return {
                regionTitle: [], //0:臺東縣
                counter: {}, //必須設為物件，因為counter的索引值是字串。臺東縣:0
                regionData: [], //0:[1][2][3]
                labels_x: [],
                data_y: []
            }
        },
        methods: {
            getTravelFoodData() {
                const vm = this;
                axios.get('https://data.moa.gov.tw/Service/OpenData/ODwsv/ODwsvTravelFood.aspx')
                    .then(function(res) {
                        console.log("完整回傳：", res);
                        //資料重構
                        res.data.forEach((item) => {
                            const getRegion = item.City;
                            if (vm.counter[getRegion] == undefined) {
                                vm.counter[getRegion] = vm.regionData.length;
                                vm.regionData.push([]);
                                vm.regionTitle[vm.counter[getRegion]] = getRegion;
                            }
                            vm.regionData[vm.counter[getRegion]].push(item)
                        });
                        //x軸
                        vm.labels_x = vm.regionTitle;
                        //y軸
                        vm.data_y = vm.regionData.map(function(item) {
                            return item.length;
                        });
                        //畫圖
                        vm.createChart();

                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        //
                    });
            },
            createChart() {
                const vm = this;
                const ctx = document.getElementById('myChart');

                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        // ...vm.labels_x 額外複製一份vm.labels_x 不會去動到原本的vm.labels_x
                        labels: [...vm.labels_x],
                        datasets: [{
                            label: '特色小吃店家數量',
                            data: [...vm.data_y],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            updateChart() {
                myChart.data.labels = labels_x
                myChart.data.datasets[0].data = data_y

                myChart.update()
            }
        },
        mounted() {
            const vm = this;
            vm.getTravelFoodData();

        },
        watch: {
            //監聽X軸
            labels_x: {
                handler() {
                    this.updateChart();
                },
                deep: true
            },

            //監聽Y軸
            data_y: {
                handler() {
                    this.updateChart();
                },
                deep: true
            }
        }
    }
    Vue.createApp(App).mount("#app")
</script>

<script>
    const viewsChartApp = {
        data() {
            return {
                nameArray: [], //0:太魯閣國家公園
                cntArray: [], //0:490
                labels_x: [],
                data_y: []
            }
        },
        methods: {
            getViewCntData() {
                const vm = this;
                axios.get('/api/views10')
                    .then(function(res) {
                        console.log("完整回傳：", res);
                        //資料重構
                        vm.nameArray = res.data.data.map(item => item.name);
                        vm.cntArray = res.data.data.map(item => item.like);

                        //x軸
                        vm.labels_x = vm.nameArray;
                        //y軸
                        vm.data_y = vm.cntArray;
                        //畫圖
                        vm.createChart();

                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        //
                    });
            },
            createChart() {
                const vm = this;
                const ctx = document.getElementById('viewsChart');

                viewsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        // ...vm.labels_x 額外複製一份vm.labels_x 不會去動到原本的vm.labels_x
                        labels: [...vm.labels_x],
                        datasets: [{
                            label: '文章瀏覽次數',
                            data: [...vm.data_y],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            updateChart() {
                myChart.data.labels = labels_x
                myChart.data.datasets[0].data = data_y

                myChart.update()
            }
        },
        mounted() {
            const vm = this;
            vm.getViewCntData();

        },
        watch: {
            //監聽X軸
            labels_x: {
                handler() {
                    this.updateChart();
                },
                deep: true
            },

            //監聽Y軸
            data_y: {
                handler() {
                    this.updateChart();
                },
                deep: true
            }
        }
    }
    Vue.createApp(viewsChartApp).mount("#viewschartapp")
</script>

<script>
    const typesApp = {
        data() {
            return {
                regionTitle: [], //0:自然景觀
                counter: {}, //必須設為物件，因為counter的索引值是字串。自然景觀:0
                regionData: [], //0:[1][2][3]
                labels_x: [],
                data_y: []
            }
        },
        methods: {
            getTravelFoodData() {
                const vm = this;
                axios.get('/api/views&types')
                    .then(function(res) {
                        console.log("完整回傳：", res);
                        //資料重構
                        res.data.data.forEach((item) => {
                            const getRegion = item.typeName;
                            if (vm.counter[getRegion] == undefined) {
                                vm.counter[getRegion] = vm.regionData.length;
                                vm.regionData.push([]);
                                vm.regionTitle[vm.counter[getRegion]] = getRegion;
                            }
                            vm.regionData[vm.counter[getRegion]].push(item)
                        });
                        //x軸
                        vm.labels_x = vm.regionTitle;
                        //y軸
                        vm.data_y = vm.regionData.map(function(item) {
                            return item.length;
                        });
                        //畫圖
                        vm.createChart();

                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        //
                    });
            },
            createChart() {
                const vm = this;
                const ctx = document.getElementById('typesChart');

                typesChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        // ...vm.labels_x 額外複製一份vm.labels_x 不會去動到原本的vm.labels_x
                        labels: [...vm.labels_x],
                        datasets: [{
                            label: '景點類別數量',
                            data: [...vm.data_y],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            updateChart() {
                myChart.data.labels = labels_x
                myChart.data.datasets[0].data = data_y

                myChart.update()
            }
        },
        mounted() {
            const vm = this;
            vm.getTravelFoodData();

        },
        watch: {
            //監聽X軸
            labels_x: {
                handler() {
                    this.updateChart();
                },
                deep: true
            },

            //監聽Y軸
            data_y: {
                handler() {
                    this.updateChart();
                },
                deep: true
            }
        }
    }
    Vue.createApp(typesApp).mount("#typeschartapp")
</script>




@endsection