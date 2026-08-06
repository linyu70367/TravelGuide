@extends('admin.layout')
@section('title','後臺管理系統')
@section('content')
<link rel="stylesheet" href="/css/admin/home.css">
<div id="app">
    <h3 class="mb-4">@{{ title }}</h3>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body d-flex align-items-center">
                    <div class="card-icon bg-primary text-white me-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <h6 class="text-muted">會員數量</h6>
                        <h3>@{{ membercnt }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body d-flex align-items-center">
                    <div class="card-icon bg-success text-white me-3">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div>
                        <h6 class="text-muted">景點數量</h6>
                        <h3>@{{ viewscnt }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="col-md-4">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="card-icon bg-warning text-white me-3">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <div>
                        <h6 class="text-muted">今日訪問</h6>
                        <h3>3,420</h3>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="col-md-8">
            <div class="card dashboard-card chart-card">
                <div class="card-body">
                    <canvas id="likeChart" class="w-100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card dashboard-card stats-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="card-icon bg-warning">
                            <i class="bi bi-heart-fill"></i>
                        </div>

                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">收藏次數 Top10</h6>
                            <small class="text-muted">景點排行榜</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <ul class="ranking-list">
                        <li v-for="(item,index) in top10likeviews" :key="item.id">

                            <div class="rank-number"
                                :class="{
                            'gold': index==0,
                            'silver': index==1,
                            'bronze': index==2
                        }">
                                @{{ index + 1 }}
                            </div>

                            <div class="rank-title">
                                @{{ item.name }}
                            </div>

                            <div class="rank-like">
                                <i class="bi bi-heart-fill" style="color: rgba(247, 19, 152, 0.86);"></i>
                                @{{ item.likes }}
                            </div>

                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card dashboard-card chart-card">
                <div class="card-body">
                    <canvas id="lookChart" class="w-100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex">
            <div class="card dashboard-card stats-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="card-icon bg-primary">
                            <i class="bi bi-eyeglasses"></i>
                        </div>

                        <div class="ms-3">
                            <h6 class="mb-0 fw-bold">瀏覽次數 Top10</h6>
                            <small class="text-muted">景點排行榜</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <ul class="ranking-list">
                        <li v-for="(item,index) in top10looksviews" :key="item.id">
                            <div class="rank-number"
                                :class="{
                            'gold': index==0,
                            'silver': index==1,
                            'bronze': index==2
                        }">
                                @{{ index + 1 }}
                            </div>

                            <div class="rank-title">
                                @{{ item.name }}
                            </div>

                            <div class="rank-like">
                                <i class="bi bi-eye-fill" style="color: rgb(15, 15, 15);"></i>
                                @{{ item.looks }}
                            </div>

                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let likeChart = null;
    let lookChart = null;

    const App = {
        data() {
            return {
                title: 'Dashboard',
                membercnt: '',
                viewscnt: '',
                top10likeviews: [],
                top10looksviews: [],
                label_like_x: [],
                data_like_y: [],
                label_look_x: [],
                data_look_y: [],
                borderColor: [
                    'rgba(255, 99, 132, 0.6)', // 1. 玫瑰粉紅
                    'rgba(54, 162, 235, 0.6)', // 2. 經典蔚藍
                    'rgba(255, 206, 86, 0.6)', // 3. 亮麗暖黃
                    'rgba(75, 192, 192, 0.6)', // 4. 薄荷青綠
                    'rgba(153, 102, 255, 0.6)', // 5. 夢幻薰衣草紫
                    'rgba(255, 159, 64, 0.6)', // 6. 活力亮橙
                    'rgba(46, 204, 113, 0.6)', // 7. 翡翠鮮綠
                    'rgba(231, 76, 60, 0.6)', // 8. 珊瑚鮮紅
                    'rgba(52, 152, 219, 0.6)', // 9. 晴空天藍
                    'rgba(155, 89, 182, 0.6)', // 10. 紫羅蘭
                    'rgba(241, 196, 15, 0.6)', // 11. 鵝黃檸檬
                    'rgba(26, 188, 156, 0.6)', // 12. 綠松石綠
                    'rgba(230, 126, 34, 0.6)', // 13. 南瓜橘紅
                    'rgba(52, 73, 94, 0.6)', // 14. 沉穩藍灰
                    'rgba(243, 156, 18, 0.6)', // 15. 琥珀金黃
                    'rgba(211, 84, 0, 0.6)', // 16. 赭石焦橙
                    'rgba(192, 57, 43, 0.6)', // 17. 赭紅磚紅
                    'rgba(142, 68, 173, 0.6)', // 18. 深邃帝王紫
                    'rgba(41, 128, 185, 0.6)', // 19. 深海湛藍
                    'rgba(39, 174, 96, 0.6)', // 20. 森林綠
                    'rgba(22, 160, 133, 0.6)', // 21. 深松石綠
                    'rgba(127, 140, 141, 0.6)' // 22. 中性石灰色
                ],
                backgroundColor: [
                    'rgb(255, 99, 132)', // 1. 玫瑰粉紅 (實色)
                    'rgb(54, 162, 235)', // 2. 經典蔚藍 (實色)
                    'rgb(255, 206, 86)', // 3. 亮麗暖黃 (實色)
                    'rgb(75, 192, 192)', // 4. 薄荷青綠 (實色)
                    'rgb(153, 102, 255)', // 5. 夢幻薰衣草紫 (實色)
                    'rgb(255, 159, 64)', // 6. 活力亮橙 (實色)
                    'rgb(46, 204, 113)', // 7. 翡翠鮮綠 (實色)
                    'rgb(231, 76, 60)', // 8. 珊瑚鮮紅 (實色)
                    'rgb(52, 152, 219)', // 9. 晴空天藍 (實色)
                    'rgb(155, 89, 182)', // 10. 紫羅蘭 (實色)
                    'rgb(241, 196, 15)', // 11. 鵝黃檸檬 (實色)
                    'rgb(26, 188, 156)', // 12. 綠松石綠 (實色)
                    'rgb(230, 126, 34)', // 13. 南瓜橘紅 (實色)
                    'rgb(52, 73, 94)', // 14. 沉穩藍灰 (實色)
                    'rgb(243, 156, 18)', // 15. 琥珀金黃 (實色)
                    'rgb(211, 84, 0)', // 16. 赭石焦橙 (實色)
                    'rgb(192, 57, 43)', // 17. 赭紅磚紅 (實色)
                    'rgb(142, 68, 173)', // 18. 深邃帝王紫 (實色)
                    'rgb(41, 128, 185)', // 19. 深海湛藍 (實色)
                    'rgb(39, 174, 96)', // 20. 森林綠 (實色)
                    'rgb(22, 160, 133)', // 21. 深松石綠 (實色)
                    'rgb(127, 140, 141)' // 22. 中性石灰色 (實色)
                ]
            }
        },
        methods: {
            async getAlldata() {
                try {
                    // Set Views & Wish
                    const vm = this;
                    let response = await axios.get('/api/views/getWishView');
                    console.log(response.data.data);

                    vm.label_like_x = response.data.data.map(function(item) {
                        return item.name;
                    });

                    vm.data_like_y = response.data.data.map(function(item) {
                        return item.wishlists.length;
                    });

                    vm.label_look_x = vm.label_like_x;
                    vm.data_look_y = response.data.data.map(function(item) {
                        return item.like;
                    });

                    let viewslike = response.data.data.map(function(item) {
                        return {
                            'name': item.name,
                            'likes': item.wishlists.length
                        }
                    });

                    vm.top10likeviews = viewslike.sort((a, b) => b.likes - a.likes).slice(0, 10);

                    let viewslooks = response.data.data.map(function(item) {
                        return {
                            'name': item.name,
                            'looks': item.like
                        }
                    });

                    vm.top10looksviews = viewslooks.sort((a, b) => b.looks - a.looks).slice(0, 10);

                    vm.viewscnt = response.data.data.length;
                } catch (error) {
                    console.log(error);
                }
            },
            createLikeChart() {
                const vm = this;
                const ctx = document.getElementById('likeChart');

                likeChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        // ...vm.labels_x 額外複製一份vm.labels_x 不會去動到原本的vm.labels_x
                        labels: [...vm.label_like_x],
                        datasets: [{
                            label: '景點收藏數',
                            data: [...vm.data_like_y],
                            borderWidth: 1,
                            borderColor: vm.borderColor,
                            backgroundColor: vm.backgroundColor,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            createLookChart() {
                const vm = this;
                const ctx = document.getElementById('lookChart');

                lookChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        // ...vm.labels_x 額外複製一份vm.labels_x 不會去動到原本的vm.labels_x
                        labels: [...vm.label_look_x],
                        datasets: [{
                            label: '景點瀏覽數',
                            data: [...vm.data_look_y],
                            borderWidth: 1,
                            borderColor: vm.borderColor,
                            backgroundColor: vm.backgroundColor,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            updatelikeChart() {
                likeChart.data.labels = [...this.label_like_x];
                likeChart.data.datasets[0].data = [...this.data_like_y];

                likeChart.update();
            },
            updatelookChart() {
                lookChart.data.labels = [...this.label_look_x];
                lookChart.data.datasets[0].data = [...this.data_look_y];

                lookChart.update();
            },
            getMembers() {
                const vm = this;
                axios.get('/admin/member/getMembers')
                    .then(function(response) {
                        console.log(response);
                        vm.membercnt = response.data.cnt;
                    })
                    .catch(function(error) {
                        console.log(error);
                    })
                    .finally(function() {
                        // always executed
                    });
            }
        },
        async mounted() {
            const vm = this;
            await vm.getAlldata();
            vm.getMembers();
            vm.createLikeChart();
            vm.createLookChart();
        },
        watch: {
            //監聽X軸
            label_like_x: {
                handler() {
                    this.updatelikeChart();
                },
                deep: true
            },
            //監聽Y軸
            data_like_y: {
                handler() {
                    this.updatelikeChart();
                },
                deep: true
            },
            //監聽X軸
            label_look_x: {
                handler() {
                    this.updatelookChart();
                },
                deep: true
            },
            //監聽Y軸
            data_look_y: {
                handler() {
                    this.updatelookChart();
                },
                deep: true
            }
        }
    };
    Vue.createApp(App).mount('#app');
</script>
@endsection