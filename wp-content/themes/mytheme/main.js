import './sass/main.scss'
import $ from "jquery";

$('.social-links-list').hide()
$('.social-links').on('mouseover', function() {
    $('.social-links-list').show(200)
    $('.social-links-open').hide(200)
    $('.social-links-close').show(200)
}).on('mouseleave', function() {
    $('.social-links-list').hide(200)
    $('.social-links-open').show(200)
    $('.social-links-close').hide(200)
})

$('.nav-bar-content').on('click', function() {
    $('.nav-screen').fadeToggle(200)
    $('.hamburger').toggleClass('active')
})

$('.modal-close').on('click', function() {
    $(this).parent().parent().fadeOut(200)
    $('body').css('overflow', 'auto')
})

$(".modal").on("click", function(event) {
    if (event.target === this) {
        $(this).fadeOut();
    }
});

$('[data-modal]').on('click', function(e) {
    e.preventDefault()
    $('#'+$(this).data('modal')).fadeIn(200)
    $('body').css('overflow', 'hidden')
})

$(document).on('click', '.form-select-toggle', function(){
    const t = $(this);
    t.toggleClass('active');
    t.closest('.form-select').find('.form-select-list').stop().slideToggle(400);
    return false;
})

$(document).on('click', '.form-select-option', function(){
    const t = $(this);
    const select = t.closest('.form-select');
    select.find('.form-select-option').removeClass('active');
    select.find('.form-select-toggle').removeClass('active');
    select.find('.form-select-toggle span').text(t.text());
    select.find('.form-select-list').stop().slideUp(400);
    select.find('.form-select-input').val(t.data('value'));
    t.addClass('active');
    return false;
})