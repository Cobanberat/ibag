<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />
    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome (ikonlar için, örn: fas fa-trash-alt) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


<!-- Bootstrap 5 CSS -->

    <title>IBAG</title>
    @vite(['resources/css/admin.css'])
    @vite(['resources/js/admin.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<div class="wrapper">

   <nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
            <span class="align-middle">İBAG Panel</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-header">Genel</li>

            <li class="sidebar-item{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <i class="align-middle" data-feather="home"></i>
                    <span class="align-middle">Ana Sayfa</span>
                </a>
            </li>
            <li class="sidebar-item{{ request()->routeIs('admin.isEkle') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.isEkle') }}">
                    <i class="align-middle" data-feather="plus-circle"></i>
                    <span class="align-middle">İş Ekle</span>
                </a>
            </li>

            <li class="sidebar-header">Ekipman Yönetimi</li>

            <li class="sidebar-item{{ request()->routeIs('admin.stock') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.stock') }}">
                    <i class="align-middle" data-feather="package"></i>
                    <span class="align-middle">Stok</span>
                </a>
            </li>
            <li class="sidebar-item{{ request()->routeIs('admin.equipments') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.equipments') }}">
                    <i class="align-middle" data-feather="tool"></i>
                    <span class="align-middle">Ekipmanlar</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.kategori') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.kategori') }}">
                    <i class="align-middle" data-feather="layers"></i>
                    <span class="align-middle">Kategoriler</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.ekipman') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.ekipman') }}">
                    <i class="align-middle" data-feather="cpu"></i>
                    <span class="align-middle">Ekipman Özellikleri</span>
                </a>
            </li>

            <li class="sidebar-header">İşlem Takibi</li>
   
            <li class="sidebar-item{{ request()->routeIs('admin.gidenGelen') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.gidenGelen') }}">
                    <i class="align-middle" data-feather="repeat"></i>
                    <span class="align-middle">Giden / Gelen İşlemler</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.equipmentStatus') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.equipmentStatus') }}">
                    <i class="align-middle" data-feather="check-circle"></i>
                    <span class="align-middle">Eşya Durumu</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.statusCheck') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.statusCheck') }}">
                    <i class="align-middle" data-feather="activity"></i>
                    <span class="align-middle">Durum Kontrolü</span>
                </a>
            </li>

            <li class="sidebar-header">Destek & Takip</li>

            <li class="sidebar-item{{ request()->routeIs('admin.fault') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.fault') }}">
                    <i class="align-middle" data-feather="alert-circle"></i>
                    <span class="align-middle">Arıza Bildirimi</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.location') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.location') }}">
                    <i class="align-middle" data-feather="map-pin"></i>
                    <span class="align-middle">Konum Takibi</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.approvalProcces') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{route('admin.approvalProcces')}}">
                    <i class="align-middle" data-feather="check-square"></i>
                    <span class="align-middle">Onay Süreci</span>
                </a>
            </li>

          

            <li class="sidebar-header">Analiz & Rapor</li>

            <li class="sidebar-item{{ request()->routeIs('admin.reporting') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.reporting') }}">
                    <i class="align-middle" data-feather="bar-chart-2"></i>
                    <span class="align-middle">Raporlama</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.dataAnalysis') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.dataAnalysis') }}">
                    <i class="align-middle" data-feather="pie-chart"></i>
                    <span class="align-middle">Veri Analizi</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.equipmentAnalysis') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.equipmentAnalysis') }}">
                    <i class="align-middle" data-feather="settings"></i>
                    <span class="align-middle">Ekipman Analizi</span>
                </a>
            </li>

            <li class="sidebar-item{{ request()->routeIs('admin.memberAnalysis') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.memberAnalysis') }}">
                    <i class="align-middle" data-feather="users"></i>
                    <span class="align-middle">Üye Analizi</span>
                </a>
            </li>

            <li class="sidebar-header">Kullanıcı</li>

            <li class="sidebar-item{{ request()->routeIs('admin.users') ? ' active' : '' }}">
                <a class="sidebar-link" href="{{ route('admin.users') }}">
                    <i class="align-middle" data-feather="user"></i>
                    <span class="align-middle">Kullanıcılar</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
	<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
            <li class="nav-item">
               <!-- From Uiverse.io by Type-Delta --> 
<label
  for="themeToggle"
  class="themeToggle st-sunMoonThemeToggleBtn"
  type="checkbox"
>
  <input type="checkbox" id="themeToggle" class="themeToggleInput" />
  <svg
    width="18"
    height="18"
    viewBox="0 0 20 20"
    fill="currentColor"
    stroke="none"
  >
    <mask id="moon-mask">
      <rect x="0" y="0" width="20" height="20" fill="white"></rect>
      <circle cx="11" cy="3" r="8" fill="black"></circle>
    </mask>
    <circle
      class="sunMoon"
      cx="10"
      cy="10"
      r="8"
      mask="url(#moon-mask)"
    ></circle>
    <g>
      <circle class="sunRay sunRay1" cx="18" cy="10" r="1.5"></circle>
      <circle class="sunRay sunRay2" cx="14" cy="16.928" r="1.5"></circle>
      <circle class="sunRay sunRay3" cx="6" cy="16.928" r="1.5"></circle>
      <circle class="sunRay sunRay4" cx="2" cy="10" r="1.5"></circle>
      <circle class="sunRay sunRay5" cx="6" cy="3.1718" r="1.5"></circle>
      <circle class="sunRay sunRay6" cx="14" cy="3.1718" r="1.5"></circle>
    </g>
  </svg>
</label>

            </li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator">4</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									4 New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-danger" data-feather="alert-circle"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Update completed</div>
												<div class="text-muted small mt-1">Restart server 12 to complete the update.</div>
												<div class="text-muted small mt-1">30m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-warning" data-feather="bell"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Lorem ipsum</div>
												<div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate hendrerit et.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-primary" data-feather="home"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Login from 192.186.1.8</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-success" data-feather="user-plus"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">New connection</div>
												<div class="text-muted small mt-1">Christina accepted your request.</div>
												<div class="text-muted small mt-1">14h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all notifications</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="message-square"></i>
								</div>
							</a>
                            <!-- From Uiverse.io by RiccardoRapelli --> 


							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="messagesDropdown">
								<div class="dropdown-menu-header">
									<div class="position-relative">
										4 New Messages
									</div>
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-5.jpg" class="avatar img-fluid rounded-circle" alt="Vanessa Tucker">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Vanessa Tucker</div>
												<div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu tortor.</div>
												<div class="text-muted small mt-1">15m ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-2.jpg" class="avatar img-fluid rounded-circle" alt="William Harris">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">William Harris</div>
												<div class="text-muted small mt-1">Curabitur ligula sapien euismod vitae.</div>
												<div class="text-muted small mt-1">2h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-4.jpg" class="avatar img-fluid rounded-circle" alt="Christina Mason">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Christina Mason</div>
												<div class="text-muted small mt-1">Pellentesque auctor neque nec urna.</div>
												<div class="text-muted small mt-1">4h ago</div>
											</div>
										</div>
									</a>
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<img src="img/avatars/avatar-3.jpg" class="avatar img-fluid rounded-circle" alt="Sharon Lessman">
											</div>
											<div class="col-10 ps-2">
												<div class="text-dark">Sharon Lessman</div>
												<div class="text-muted small mt-1">Aenean tellus metus, bibendum sed, posuere ac, mattis non.</div>
												<div class="text-muted small mt-1">5h ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all messages</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
              </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                <span class="avatar bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center me-1" style="width:36px;height:36px;font-weight:700;font-size:1.2rem;">B</span>
                <span class="text-dark">Berat Çoban</span>
              </a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				@yield('content')
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-12 text-center">
							<p class="mb-0">
								&copy; {{ date('Y') }} <strong>İBag</strong> | Tüm hakları saklıdır.
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
        // Line chart
        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
                    "Dec"
                ],
                datasets: [{
                    label: "Sales ($)",
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: window.theme.primary,
                    data: [
                        2115,
                        1562,
                        1584,
                        1892,
                        1587,
                        1923,
                        2566,
                        2448,
                        2805,
                        3438,
                        2917,
                        3327
                    ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pie chart
        new Chart(document.getElementById("chartjs-dashboard-pie"), {
            type: "pie",
            data: {
                labels: ["Chrome", "Firefox", "IE"],
                datasets: [{
                    data: [4306, 3801, 1689],
                    backgroundColor: [
                        window.theme.primary,
                        window.theme.warning,
                        window.theme.danger
                    ],
                    borderWidth: 5
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 75
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Bar chart
        new Chart(document.getElementById("chartjs-dashboard-bar"), {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
                    "Dec"
                ],
                datasets: [{
                    label: "This year",
                    backgroundColor: window.theme.primary,
                    borderColor: window.theme.primary,
                    hoverBackgroundColor: window.theme.primary,
                    hoverBorderColor: window.theme.primary,
                    data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                    barPercentage: .75,
                    categoryPercentage: .5
                }]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false
                        },
                        stacked: false,
                        ticks: {
                            stepSize: 20
                        }
                    }],
                    xAxes: [{
                        stacked: false,
                        gridLines: {
                            color: "transparent"
                        }
                    }]
                }
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var markers = [{
                coords: [31.230391, 121.473701],
                name: "Shanghai"
            },
            {
                coords: [28.704060, 77.102493],
                name: "Delhi"
            },
            {
                coords: [6.524379, 3.379206],
                name: "Lagos"
            },
            {
                coords: [35.689487, 139.691711],
                name: "Tokyo"
            },
            {
                coords: [23.129110, 113.264381],
                name: "Guangzhou"
            },
            {
                coords: [40.7127837, -74.0059413],
                name: "New York"
            },
            {
                coords: [34.052235, -118.243683],
                name: "Los Angeles"
            },
            {
                coords: [41.878113, -87.629799],
                name: "Chicago"
            },
            {
                coords: [51.507351, -0.127758],
                name: "London"
            },
            {
                coords: [40.416775, -3.703790],
                name: "Madrid "
            }
        ];
        var map = new jsVectorMap({
            map: "world",
            selector: "#world_map",
            zoomButtons: true,
            markers: markers,
            markerStyle: {
                initial: {
                    r: 9,
                    strokeWidth: 7,
                    stokeOpacity: .4,
                    fill: window.theme.primary
                },
                hover: {
                    fill: window.theme.primary,
                    stroke: window.theme.primary
                }
            },
            zoomOnScroll: false
        });
        window.addEventListener("resize", () => {
            map.updateSize();
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
        var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
        document.getElementById("datetimepicker-dashboard").flatpickr({
            inline: true,
            prevArrow: "<span title=\"Previous month\">&laquo;</span>",
            nextArrow: "<span title=\"Next month\">&raquo;</span>",
            defaultDate: defaultDate
        });
    });
</script>
    @stack('scripts')
</body>
<!-- Bootstrap JS (opsiyonel, modal/dropdown gibi dinamik bileşenler için) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap 5 JS (bundle, Popper ile birlikte) -->
</html>
<style>
 /* From Uiverse.io by Type-Delta */ 
/* a clone from joshwcomeau.com 
 * but this version runs on pure CSS
 */

.themeToggle {
  color: #bbb;
  width: 3em;
}

.st-sunMoonThemeToggleBtn {
  position: relative;
  cursor: pointer;
}

.st-sunMoonThemeToggleBtn .themeToggleInput {
  opacity: 0;
  width: 100%;
  aspect-ratio: 1;
}

.st-sunMoonThemeToggleBtn svg {
  position: absolute;
  left: 0;
  width: 70%;
  height: 70%;
  transition: transform 0.4s ease;
  transform: rotate(40deg);
}

.st-sunMoonThemeToggleBtn svg .sunMoon {
  transform-origin: center center;
  transition: inherit;
  transform: scale(1);
}

.st-sunMoonThemeToggleBtn svg .sunRay {
  transform-origin: center center;
  transform: scale(0);
}

.st-sunMoonThemeToggleBtn svg mask > circle {
  transition: transform 0.64s cubic-bezier(0.41, 0.64, 0.32, 1.575);
  transform: translate(0px, 0px);
}

.st-sunMoonThemeToggleBtn svg .sunRay2 {
  animation-delay: 0.05s !important;
}
.st-sunMoonThemeToggleBtn svg .sunRay3 {
  animation-delay: 0.1s !important;
}
.st-sunMoonThemeToggleBtn svg .sunRay4 {
  animation-delay: 0.17s !important;
}
.st-sunMoonThemeToggleBtn svg .sunRay5 {
  animation-delay: 0.25s !important;
}
.st-sunMoonThemeToggleBtn svg .sunRay5 {
  animation-delay: 0.29s !important;
}

.st-sunMoonThemeToggleBtn .themeToggleInput:checked + svg {
  transform: rotate(90deg);
}
.st-sunMoonThemeToggleBtn .themeToggleInput:checked + svg mask > circle {
  transform: translate(16px, -3px);
}
.st-sunMoonThemeToggleBtn .themeToggleInput:checked + svg .sunMoon {
  transform: scale(0.55);
}
.st-sunMoonThemeToggleBtn .themeToggleInput:checked + svg .sunRay {
  animation: showRay1832 0.4s ease 0s 1 forwards;
}

@keyframes showRay1832 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}


</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @push('scripts')
<script>
  if (window.feather) { feather.replace(); }
</script>
@endpush
