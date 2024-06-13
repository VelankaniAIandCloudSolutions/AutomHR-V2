import { Pipe, PipeTransform } from "@angular/core";
import { DomSanitizer, SafeUrl } from "@angular/platform-browser";

@Pipe({
  name: "sortBy"
})
export class SortByPipe implements PipeTransform {
  transform(items: any[], sortedBy: string): any {
    //console.log(items.sort((a, b) => {return b[sortedBy] - a[sortedBy]}));

    return items.sort((a, b) => {
      return b[sortedBy] - a[sortedBy];
    });
  }
}

@Pipe({
  name: "filter"
})
export class FilterPipe implements PipeTransform {
  transform(items: any[], searchText: string): any[] {
    if (!items) return [];
    if (!searchText) return items;
    searchText = searchText.toLowerCase();
   //console.log(items);
    return items.filter(it => {
      return it.fullname.toLowerCase().includes(searchText);
    });
  }
}

@Pipe({
  name: "list"
})
export class ListPipe implements PipeTransform {
  transform(items, searchText: string): any[] {
    if (!items) return [];
    if (!searchText) return items;
    searchText = searchText.toLowerCase();
    return (items = items.filter(item => {
      var it = JSON.stringify(item);
      return it.toLowerCase().includes(searchText);
    }));
  }
}

@Pipe({
  name: "orderby"
})
export class OrderByPipe implements PipeTransform {
  transform(details: any[], args: string): any {
    if (args == "asc") {
      details.sort(
        (x: any, y: any): any => {
          return y.name < x.name;
        }
      );
    }
    if (args == "desc") {
      details.sort(
        (x: any, y: any): any => {
          return y.name > x.name;
        }
      );
    }
    return details;
  }
}

@Pipe({ name: 'keys',  pure: false })
export class KeysPipe implements PipeTransform {
    transform(value: any, args: any[] = null): any {
        console.log(Object.keys)
        return Object.keys(value)//.map(key => value[key]);
    }
}
