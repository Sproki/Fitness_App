const darkModeHandler = ()=>{
    if (
        localStorage.theme === "dark" ||
        (!("theme" in localStorage) &&
        window.matchMedia("(prefers-color-scheme: dark)").matches)
    ) {
        document.documentElement.classList.remove("dark");
        document.documentElement.classList.add("light");
        localStorage.theme = "light";
    } else {
        document.documentElement.classList.add("dark");
        document.documentElement.classList.remove("light");
        localStorage.theme = "dark";
    }
};