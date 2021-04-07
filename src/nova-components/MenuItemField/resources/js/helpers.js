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
