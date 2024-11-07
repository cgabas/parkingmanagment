class CGparking {
    async back() {
        document.cookie = 'userRegis=; addHour=; addMinute=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
        window.location = 'index.php';
    }

    async gotoRegis() {
        document.cookie='userRegis=true; path=/; SameSite=Lax Secure';
        window.location = 'register.php';
    }

    async gotoIndex(surname) {
        alert(`Welcome ${surname}`);
        document.cookie = 'userRegis=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC; SameSite=Lax';
        window.location = 'index.php';
    }

    async submit(id) {
        document.getElementById(id).click();
    }

    async comeBack() {
        window.location = 'register.php';
    }

    async changeTimeDurDisplay(inputSync, idName) {
        
    }
}

let addTime = 0;

// make global
window.addTime = addTime;
window.CGparking = CGparking;

// export
export {
    CGparking,
    addTime
};