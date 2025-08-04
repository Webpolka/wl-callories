import Swiper from "swiper";
import SwiperCore, { Navigation } from "swiper/core";

// configure Swiper to use modules
SwiperCore.use([Navigation]);
/*---------------------------------------------------------------------------------------------------------------------
Callorie calculator script
------------------------------------------------------------------------------------------------------------------------*/

document.addEventListener("DOMContentLoaded", () => {
	const calorieSliderWrap = document.querySelector("#calorie-slider");

	const totalGarnirSlides = document.querySelectorAll("#garnirs-swiper .swiper-slide").length;
	const randomGarnirIndex = Math.floor(Math.random() * totalGarnirSlides);

	const totalSaladSlides = document.querySelectorAll("#salads-swiper .swiper-slide").length;
	const randomSaladsIndex = Math.floor(Math.random() * totalSaladSlides);

	if (calorieSliderWrap) {
		const swiperGarnirs = new Swiper("#garnirs-swiper", {
			// Optional parameters
			initialSlide: randomGarnirIndex,
			loop: true,
			direction: "vertical",
			autoHeight: true,
			spaceBetween: 3,

			// Navigation arrows
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},

			on: {
				slideChange: function () {
					updateSlideImages(this, "#garnirs-swiper");
				},
				slideChangeTransitionEnd: updateInfo,
				touchStart: function () {
					// ничего не делаем, подготовка
				},
				touchMove: function () {
					const activeIndex = this.activeIndex;
					const slides = this.slides;

					// Получаем текущий, предыдущий и следующий слайды
					const currentSlide = slides[activeIndex];
					const prevSlide = slides[activeIndex - 1];
					const nextSlide = slides[activeIndex + 1];

					// Общий прогресс перетаскивания (например, из deltaX)
					const deltaX = this.touchEventsData.currentX - this.touchEventsData.startX;
					const slideWidth = this.width;

					// Расчёт прогресса в диапазоне от -1 до 1
					const progress = deltaX / slideWidth;

					// Функция для установки стилей
					const setSlideStyle = (slide) => {
						if (slide) {
							slide.style.opacity = 0.5; // полупрозрачность
							slide.style.transform = `scale(0.9)`;
						}
					};

					// Устанавливаем стили для текущего, предыдущего и следующего слайда
					setSlideStyle(currentSlide, progress);
					setSlideStyle(prevSlide, progress - 1); // немного смещено
					setSlideStyle(nextSlide, progress + 1);
				},
				touchEnd: function () {
					const slides = this.slides;
					// Возвращаем все стили к исходным с плавной анимацией
					slides.forEach((slide) => {
						slide.style.opacity = "";
						slide.style.transform = "";
					});
				},
			},
		});

		const swiperSalats = new Swiper("#salads-swiper", {
			// Optional parameters

			initialSlide: randomSaladsIndex,
			direction: "vertical",
			autoHeight: true,
			loop: true,
			spaceBetween: 3,

			// Navigation arrows
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},
			on: {
				slideChange: function () {
					updateSlideImages(this, "#salads-swiper");
				},
				slideChangeTransitionEnd: updateInfo,
				touchStart: function () {
					// ничего не делаем, подготовка
				},
				touchMove: function () {
					const activeIndex = this.activeIndex;
					const slides = this.slides;

					// Получаем текущий, предыдущий и следующий слайды
					const currentSlide = slides[activeIndex];
					const prevSlide = slides[activeIndex - 1];
					const nextSlide = slides[activeIndex + 1];

					// Общий прогресс перетаскивания (например, из deltaX)
					const deltaX = this.touchEventsData.currentX - this.touchEventsData.startX;
					const slideWidth = this.width;

					// Расчёт прогресса в диапазоне от -1 до 1
					const progress = deltaX / slideWidth;

					// Функция для установки стилей
					const setSlideStyle = (slide) => {
						if (slide) {
							slide.style.opacity = 0.5; // полупрозрачность
							slide.style.transform = `scale(0.9)`;
						}
					};

					// Устанавливаем стили для текущего, предыдущего и следующего слайда
					setSlideStyle(currentSlide, progress);
					setSlideStyle(prevSlide, progress - 1); // немного смещено
					setSlideStyle(nextSlide, progress + 1);
				},
				touchEnd: function () {
					const slides = this.slides;
					// Возвращаем все стили к исходным с плавной анимацией
					slides.forEach((slide) => {
						slide.style.opacity = "";
						slide.style.transform = "";
					});
				},
			},
		});

		// Функция обновления изображений
		function updateSlideImages(swiperInstance, sliderEl) {
			const initialRadius = Number(document.querySelector(sliderEl).dataset.initial);
			const slides = swiperInstance.slides;
			const currentIndex = swiperInstance.activeIndex;

			const total = slides.length;
			const radius = Math.min(initialRadius, Math.floor(total / 2)); // менять 2 с двух сторон

			for (let offset = -radius; offset <= radius; offset++) {
				let index = (currentIndex + offset + total) % total; // цикл
				const slide = slides[index];
				const img = slide.querySelector("img");
				if (img && img.dataset.src && img.dataset.updated == "false") {
					img.src = img.dataset.src;
					img.dataset.updated = "true";
				}
			}
		}

		function updateInfo() {
			// Получаем активный слайд
			const activeGarnirSlide = calorieSliderWrap.querySelector("#garnirs-swiper .swiper-slide-active");
			const activeSaladSlide = calorieSliderWrap.querySelector("#salads-swiper .swiper-slide-active");

			if (!activeGarnirSlide || !activeSaladSlide) return;

			const linkGarnir = activeGarnirSlide.querySelector(".calorie-card");
			const linkSalad = activeSaladSlide.querySelector(".calorie-card");

			if (!linkGarnir || !linkSalad) return;

			// Извлекаем атрибуты из гарниров
			const titleGarnir = linkGarnir.dataset.title;
			const pGarnir = parseFloat(linkGarnir.dataset.p);
			const fGarnir = parseFloat(linkGarnir.dataset.f);
			const cGarnir = parseFloat(linkGarnir.dataset.c);
			const kkalGarnir = parseFloat(linkGarnir.dataset.kkal);
			const servingGarnir = parseFloat(linkGarnir.dataset.serving);

			// Извлекаем атрибуты из салатов
			const titleSalad = linkSalad.dataset.title;
			const pSalad = parseFloat(linkSalad.dataset.p);
			const fSalad = parseFloat(linkSalad.dataset.f);
			const cSalad = parseFloat(linkSalad.dataset.c);
			const kkalSalad = parseFloat(linkSalad.dataset.kkal);
			const servingSalad = parseFloat(linkSalad.dataset.serving);

			// Обновляем контейнеры для гарниров
			calorieSliderWrap.querySelector("#calorie-garnirs-title").textContent = titleGarnir.trim();
			calorieSliderWrap.querySelector("#calorie-garnirs-protein").textContent = pGarnir.toFixed(1);
			calorieSliderWrap.querySelector("#calorie-garnirs-fats").textContent = fGarnir.toFixed(1);
			calorieSliderWrap.querySelector("#calorie-garnirs-carbo").textContent = cGarnir.toFixed(1);
			calorieSliderWrap.querySelector("#calorie-garnirs-kkal").textContent = kkalGarnir.toFixed(1);

			// Обновляем контейнеры для салатов
			calorieSliderWrap.querySelector("#calorie-salads-title").textContent = titleSalad.trim();
			calorieSliderWrap.querySelector("#calorie-salads-protein").textContent = pSalad.toFixed(1);
			calorieSliderWrap.querySelector("#calorie-salads-fats").textContent = fSalad.toFixed(1);
			calorieSliderWrap.querySelector("#calorie-salads-carbo").textContent = cSalad.toFixed(1);
			calorieSliderWrap.querySelector("#calorie-salads-kkal").textContent = kkalSalad.toFixed(1);

			// Считаем массу на тарелке
			const onplateWeight = Number(servingSalad) + Number(servingGarnir);

			// Считаем ккал на тарелке
			const onplateGarnir = ((kkalGarnir / 100) * (servingGarnir / 100) * 100).toFixed(1);
			const onplateSalad = ((kkalSalad / 100) * (servingSalad / 100) * 100).toFixed(1);
			const onplateKkal = Number((Number(onplateGarnir) + Number(onplateSalad)).toFixed(1));

			// Считаем белки на тарелке
			const onplateProteinGarnir = ((pGarnir / 100) * (servingGarnir / 100) * 100).toFixed(1);
			const onplateProteinSalad = ((pSalad / 100) * (servingSalad / 100) * 100).toFixed(1);
			const onplateProtein = Number((Number(onplateProteinGarnir) + Number(onplateProteinSalad)).toFixed(1));

			// Считаем жиры на тарелке
			const onplateFatsGarnir = ((fGarnir / 100) * (servingGarnir / 100) * 100).toFixed(1);
			const onplateFatsSalad = ((fSalad / 100) * (servingSalad / 100) * 100).toFixed(1);
			const onplateFats = Number((Number(onplateFatsGarnir) + Number(onplateFatsSalad)).toFixed(1));

			// Считаем углеводы на тарелке
			const onplateCarboGarnir = ((cGarnir / 100) * (servingGarnir / 100) * 100).toFixed(1);
			const onplateCarboSalad = ((cSalad / 100) * (servingSalad / 100) * 100).toFixed(1);
			const onplateCarbo = Number((Number(onplateCarboGarnir) + Number(onplateCarboSalad)).toFixed(1));

			// Выводим результаты вычислений в заголовке
			calorieSliderWrap.querySelector("#calorie-sum-weight").textContent = onplateWeight;
			calorieSliderWrap.querySelector("#calorie-sum-kkal").textContent = onplateKkal;
			calorieSliderWrap.querySelector("#calorie-sum-protein").textContent = onplateProtein;
			calorieSliderWrap.querySelector("#calorie-sum-fats").textContent = onplateFats;
			calorieSliderWrap.querySelector("#calorie-sum-carbo").textContent = onplateCarbo;
		}
	}
});

document.addEventListener("DOMContentLoaded", () => {
	document.documentElement.insertAdjacentHTML(
		"beforeend",
		`
  	  <div class="calorie-modal_overlay" id="myModal">		  
		  <div class="calorie-modal_content">			
			<div class="calorie-modal_header">
			  <div id="modalTitle"></div>
			  <button class="calorie-close_header" data-closeModal aria-label="Закрыть модальное окно">
				<svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg"><g filter="url(#close_svg__a)"><circle cx="16" cy="16" r="12" fill="#fff"></circle><path d="m20 12-8 8m0-8 8 8" stroke="#000000" stroke-width="2" stroke-linecap="round"></path></g><defs><filter id="close_svg__a" x="0" y="0" width="32" height="32" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB"><feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood><feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix><feOffset></feOffset><feGaussianBlur stdDeviation="2"></feGaussianBlur><feColorMatrix values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"></feColorMatrix><feBlend in2="BackgroundImageFix" result="effect1_dropShadow"></feBlend><feBlend in="SourceGraphic" in2="effect1_dropShadow" result="shape"></feBlend></filter></defs></svg>
			  </button>
			</div>	
			<div class="calorie-modal_body" id="myModalContent"></div>	
			 <div class="calorie-modal_footer"></div>		  
		  </div>
		</div>`
	);

	function getCSSVariableValue(element, variableName) {
		// Используем getComputedStyle для получения стилей, примененных к элементу
		const style = getComputedStyle(element);
		const value = style.getPropertyValue(variableName);
		return value.trim() || null;
	}

	const accentCalc = getCSSVariableValue(document.querySelector("#calorie-calculator"), "--wl-accent-color");
	const accentSlider = getCSSVariableValue(document.querySelector("#calorie-slider"), "--wl-accent-color");
	const accentColor = accentCalc || accentSlider;

	document.querySelector("#myModal").setAttribute("style", `--wl-accent-color:${accentColor};`);
});

// Обработка модалки
jQuery(document).ready(function ($) {
	var scrollBarWidth = $(window).outerWidth() - $(document).width();
	// Отключить скролл
	function disableScroll() {
		$("body").css({
			overflow: "hidden",
			"padding-right": `${scrollBarWidth}px`,
		});
	}

	// Включить скролл
	function enableScroll() {
		$("body").css({
			overflow: "",
			"padding-right": "",
		});
	}

	$(".wl-open-modal").on("click", function (e) {
		e.preventDefault();
		var postId = $(this).data("postid");
		$("#myModalContent").html("Загрузка...");
		$("#myModal").addClass("show"); // или используйте свою реализацию модального окна
		disableScroll();

		$.ajax({
			url: MyAjax.ajaxurl, // глобальная переменная в админке, для фронта нужно определить свою
			type: "post",
			data: {
				action: "get_custom_fields",
				post_id: postId,
			},
			success: function (response) {
				$("#myModalContent").html(response);
			},
		});
	});

	// Закрытие модального окна
	$("[data-closeModal]").on("click", function () {
		$("#myModal").removeClass("show");
		enableScroll();
	});

	const form = document.getElementById("calorieForm");
	const resultDiv = document.getElementById("calorie-results");
	const modalTrue = form.querySelector('button[data-modal="true"]');

	if (form && resultDiv) {
		form.addEventListener("submit", (event) => {
			event.preventDefault();
			// Считываем данные из формы
			const gender = form.gender.value; // 'male' или 'female'
			const age = +form.age.value; // возраст, целое число
			const height = +form.height.value; // рост (см)
			const weight = +form.weight.value; // вес (кг)
			const activity = +form.lifestyle.value; // коэффициент активности
			const goal = form.goal.value; // 'loss', 'maintain' или 'gain'
			// 1) Базальный метаболизм (BMR)
			// Формула Харриса–Бенедикта
			const bmrHarris =
				gender === "male" ? 88.36 + 13.4 * weight + 4.8 * height - 5.7 * age : 447.6 + 9.2 * weight + 3.1 * height - 4.3 * age;
			// Формула Миффлина–Сан Жеора
			const bmrMifflin = 10 * weight + 6.25 * height - 5 * age + (gender === "male" ? 5 : -161);
			// 2) Учёт образа жизни → TDEE
			const tdeeHarris = Math.round(bmrHarris * activity);
			const tdeeMifflin = Math.round(bmrMifflin * activity);
			// 3) Корректировка калорийности по цели
			const adjustments = {
				loss: { min: -500, max: -300 },
				maintain: { min: -100, max: 100 },
				gain: { min: 300, max: 500 },
			};
			const adj = adjustments[goal];
			function getRange(tdee) {
				return {
					min: Math.round(tdee + adj.min),
					max: Math.round(tdee + adj.max),
				};
			}
			const rangeMiddle = getRange((tdeeHarris + tdeeMifflin) / 2);
			const rangeH = getRange(tdeeHarris);
			const rangeM = getRange(tdeeMifflin);
			// 4) Распределение макронутриентов
			const macroRatios = {
				loss: { p: 0.3, f: 0.25, c: 0.45, t: "потери веса" },
				maintain: { p: 0.25, f: 0.3, c: 0.45, t: "удержания веса" },
				gain: { p: 0.25, f: 0.25, c: 0.5, t: "набора веса" },
			};
			const ratios = macroRatios[goal];
			function calcMacros(calories) {
				return {
					proteins: Math.round((calories * ratios.p) / 4), // 1 г белка = 4 ккал
					fats: Math.round((calories * ratios.f) / 9), // 1 г жира = 9 ккал
					carbs: Math.round((calories * ratios.c) / 4), // 1 г углеводов = 4 ккал
				};
			}

			// Берём среднее от всех четырёх границ для расчёта макро
			const avgCalories = Math.round((rangeH.min + rangeH.max + rangeM.min + rangeM.max) / 4);
			const macros = calcMacros(avgCalories);

			const resHtml = `
			  <div class="calculation-result-inner">
				<h3>Расчет суточной нормы калорий :</h3>
				 <ul>
					<li>по Харрису–Бенедикту ${tdeeHarris} ккал/день</li>
				 	<li>по Миффлину–Сан Жеора ${tdeeMifflin} ккал/день</li>
				 </ul>

				 <h3>Рекомендации для ${ratios.t}</h3>		
				 <ul>
				 	<li>диапазон каллорий ${rangeMiddle.min}–${rangeMiddle.max} </li>
				 	<li>суточная норма белков : ${macros.proteins} грамм</li>
				 	<li>суточная норма жиров : ${macros.fats} грамм</li>
				 	<li>суточная норма углеводов : ${macros.carbs} грамм</li>
				</ul>
			  </div>`;

			if (modalTrue) {
				$("#myModal").addClass("show");
				$("#myModalContent").html(resHtml);
				disableScroll();
			} else {
				// 5) Вывод результатов
				resultDiv.innerHTML = resHtml;
				// Скролл к элементу
				resultDiv.scrollIntoView({ behavior: "smooth", block: "start" });
			}
		});

		// Сбрасываем результаты
		form.addEventListener("reset", function (event) {
			if (modalTrue) {
				$("#myModalContent").html("");
				$("#myModal").removeClass("show");
				enableScroll();
			} else {
				const resultDiv = document.getElementById("calorie-results");
				resultDiv.innerHTML = "";
			}
		});

		// Ограничитель min/max в input
		const inputs = document.querySelectorAll('input[type="number"]');
		inputs.forEach((input) => {
			input.addEventListener("input", () => {
				const max = parseFloat(input.getAttribute("max"));
				let value = parseFloat(input.value);

				if (!isNaN(max) && !isNaN(value)) {
					if (value > max) {
						input.value = max; // Ограничение по max
					}
				}
			});

			input.addEventListener("keydown", (e) => {
				// Разрешенные клавиши:
				const allowedKeys = ["Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"];

				// Проверка, если это цифра или запятая или точка
				const isNumber = e.key >= "0" && e.key <= "9";

				// Разрешаем, если клавиша есть в списке разрешенных или это разрешенная клавиша
				if (allowedKeys.includes(e.key) || isNumber) {
					// Можно оставить
					return;
				} else {
					e.preventDefault(); // блокируем все остальные символы
				}
			});
		});
	}
});
