import { Component, OnInit, viewChild } from '@angular/core';
import { MatProgressSpinner } from '@angular/material/progress-spinner';

@Component({
  selector: 'app-spinner',
  imports: [MatProgressSpinner],
  templateUrl: './spinner.component.html',
  styleUrl: './spinner.component.scss',
})
export class SpinnerComponent implements OnInit {
  protected spinner = viewChild(MatProgressSpinner);

  ngOnInit() {
    const spinner = this.spinner();
    if (spinner) {
      spinner.diameter = 40;
    }
  }
}
