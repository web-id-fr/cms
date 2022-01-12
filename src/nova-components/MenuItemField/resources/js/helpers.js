import Vue from 'vue';
import Toasted from 'vue-toasted';

Vue.use(Toasted);

export function mapChildren(element) {
    let children = element.children || [];

    for (let child of children) {
        if (child.menuable) {
            for (let attribute of Object.keys(child.menuable)) {
                child[attribute] = child.menuable[attribute]
            }
        }
    }

    return children;
}

export function removeChildren(value, menuItem) {
    if (_.findIndex(value.children, {
        'id': menuItem.id,
        'menuable_type': menuItem.menuable_type
    }) >= 0) {
        menuItem.isSelected = false;
        value.children.splice(_.findIndex(value.children, menuItem), 1);
        errorToast(this.__('The menu item has been removed to the list'));
        return;
    } else {
        value.children.filter(function (elem) {
            if (_.findIndex(elem.children, {
                'id': menuItem.id,
                'menuable_type': menuItem.menuable_type
            }) >= 0) {
                removeChildren(elem, menuItem)
            } else {
                elem.children.filter(function (elem) {
                    if (typeof elem.children === "object") {
                        removeChildren(elem, menuItem)
                    }
                })
            }
        })
    }
}

export function showToast(message, defaultMessage = 'An unexpected error occured.') {
    Vue.toasted.show(message || defaultMessage, {
        duration: 6000,
        position: 'bottom-right',
        action: {
            text: 'x',
            onClick: (e, toastObject) => {
                toastObject.goAway(0);
            }
        }
    });
}

export function successToast(message, defaultMessage = 'An unexpected error occured.') {
    Vue.toasted.success(message || defaultMessage, {
        duration: 6000,
        position: 'bottom-right',
        action: {
            text: 'x',
            onClick: (e, toastObject) => {
                toastObject.goAway(0);
            }
        }
    });
}

export function errorToast(message, defaultMessage = 'An unexpected error occured.') {
    Vue.toasted.error(message || defaultMessage, {
        duration: 6000,
        position: 'bottom-right',
        action: {
            text: 'x',
            onClick: (e, toastObject) => {
                toastObject.goAway(0);
            }
        }
    });
}

export function getTranslatedValue(value, locale) {
    if (!value[locale]) {
        if (value[locale + 1]) {
            return value[locale + 1];
        } else if (value[locale - 1]) {
            return value[locale - 1];
        } else {
            return value[Object.keys(value)[0]];
        }
    } else {
        return value[locale];
    }
}

export function getMenuItemType(menuItem) {
    if (menuItem.menuable_type === "App\\Models\\Template") {
        return "Page";
    }
    if (menuItem.menuable_type === "Webid\\Cms\\App\\Models\\Menu\\MenuCustomItem") {
        return "Custom item";
    }

    return menuItem.menuable_type;
}
