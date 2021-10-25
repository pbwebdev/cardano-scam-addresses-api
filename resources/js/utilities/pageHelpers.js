const windowUrl = new URL(window.location);
export const defaultPager = parseInt(windowUrl.searchParams.get('per_page') || 50);


export const getPageNumber = url => {
    const apiUrl = new URL(url);

    return apiUrl.searchParams.get('page');
}

export const getPerPage = url => {
    const apiUrl = new URL(url);

    return apiUrl.searchParams.get('per_page');
}

export const correctLink = url => {
    if (!url) {
        return '#';
    }


    const windowUrl = new URL(window.location);

    windowUrl.searchParams.set('page', getPageNumber(url))
    windowUrl.searchParams.set('per_page', getPerPage(url))

    return windowUrl.toString();
};
