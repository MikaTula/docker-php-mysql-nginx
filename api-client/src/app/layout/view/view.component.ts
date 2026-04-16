import { Component } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { MenuComponent } from '../../common/menu/menu.component';

@Component({
  selector: 'app-view',
  imports: [RouterOutlet, MenuComponent],
  templateUrl: './view.component.html',
  styleUrl: './view.component.scss',
})
export class ViewComponent {}
