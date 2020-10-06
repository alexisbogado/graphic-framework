/**
 * @author Alexis Bogado
 * @package graphic-framework
 */

const navBar = document.getElementById('nav-menu')
const navItems = document.getElementById('nav-menu-items')
const navLinks = navBar.querySelectorAll('.nav-item.nav-link')
const sections = document.querySelectorAll('section')
const hash = window.location.hash

$(document).ready(() => {
    $('#preloader').fadeOut(() => {
        document.getElementById('preloader').remove()
        document.body.classList.remove('overflow-hidden')
    })


    if (hash.length && hash !== '#!')
        scroll(hash)

    navLinks.forEach((navLink) => {
        navLink.addEventListener('click', () => {
            if (!navLink.hash) return
            
            scroll(navLink.hash)
        })
    })

    window.addEventListener('scroll', changeActiveMenuItem)
})

scroll = (id) => {
    const target = document.getElementById(id.substr(1, id.length))
    
    if (!target) return
    
    let topSpace = 0
    if (navBar) {
        if (navItems.classList.contains('show'))
            navItems.classList.remove('show')
        
        topSpace = navBar.offsetHeight
    }

    $('html, body').animate({
        scrollTop: ((target.offsetTop - topSpace) + 1)
    }, 1000)

    changeActiveMenuItem()
}

changeActiveMenuItem = () => {
    const currentTop = window.scrollY
    const marginTop = 100
    const paddingTopPercentage = 7
    
    sections.forEach((section) => {
        let top = (section.offsetTop - navBar.offsetHeight)
        top = (top - (marginTop + ((top * paddingTopPercentage) / 100)))
        
        if (currentTop < top) return

        navLinks.forEach((navLink) => {
            if (navLink.dataset.sectionId == section.id)
                navLink.classList.add('active')
            else
                navLink.classList.remove('active')
        })
    })
}