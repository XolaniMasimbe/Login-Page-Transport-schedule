const signUpButton=document.getElementById('signUpButton');
const signInButton=document.getElementById('signInButton');
const signInForm=document.getElementById('signIn');
const signUpForm=document.getElementById('signup');

signUpButton.addEventListener('click',function(){
    signInForm.style.display="none";
    signUpForm.style.display="block";
})
signInButton.addEventListener('click', function(){
    signInForm.style.display="block";
    signUpForm.style.display="none";
})
document.addEventListener('DOMContentLoaded', () => {
    console.log('Document loaded');
    const headings = document.querySelectorAll('.slideshow-heading');
    let currentIndex = 0;

    function showHeading(index) {
        console.log(`Showing heading ${index}`);
        headings.forEach((heading, i) => {
            heading.style.opacity = i === index ? '1' : '0';
        });
    }

    function startSlideshow() {
        showHeading(currentIndex);
        currentIndex = (currentIndex + 1) % headings.length;
        setTimeout(startSlideshow, 5000); // Change heading every 5 seconds
    }

    startSlideshow();
});
