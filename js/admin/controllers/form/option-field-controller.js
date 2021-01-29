import ApplicationController from '../../../common/application-controller';

export default class extends ApplicationController {
  #observer;

  static targets = ['toggle', 'accordion'];

  connect() {
    if (this.hasToggleTarget) {
      this.toggleAccordion({ target: { checked: this.toggleTarget.checked } });
    }
  }

  get isOpen() {
    if (!this.hasAccordionTarget) return false;

    return !this.accordionTarget.classList.contains('closed');
  }

  get innerRow() {
    if (this.hasAccordionTarget) {
      return this.accordionTarget?.querySelector('.row--inner');
    }

    return null;
  }

  get accordionInnerHeight() {
    if (!this.hasAccordionTarget) {
      return 0;
    }

    return (
      this.accordionTarget?.querySelector('.row--inner')?.offsetHeight || 0
    );
  }

  recomputeLayout() {
    if (this.isOpen) {
      this.openAccordion();
    }
  }

  recomputeParentLayout() {
    let parent = this.element.parentElement;

    while (parent) {
      if (parent.formOptionFieldController) {
        parent.formOptionFieldController.recomputeLayout();
      }

      parent = parent.parentElement;
    }
  }

  resizeContent() {
    this.accordionTarget.style.height = `${this.accordionInnerHeight}px`;
  }

  openAccordion() {
    if (this.hasAccordionTarget) {
      this.accordionTarget.classList.remove('closed');
      this.resizeContent();

      this.#observer = new ResizeObserver(() => this.resizeContent());
      this.#observer.observe(this.innerRow);
    }
  }

  closeAccordion() {
    if (this.hasAccordionTarget) {
      this.accordionTarget.classList.add('closed');
      this.accordionTarget.style.height = 0;

      if (this.#observer) {
        this.#observer.disconnect();
        this.#observer = null;
      }
    }
  }

  toggleAccordion(event) {
    const { target } = event;

    if (target.checked) {
      this.openAccordion();
    } else {
      this.closeAccordion();
    }

    this.later(() => {
      this.recomputeParentLayout();
    }, 100);
  }
}
