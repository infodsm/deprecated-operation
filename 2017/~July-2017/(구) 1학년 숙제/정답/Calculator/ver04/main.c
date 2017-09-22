#include <stdio.h>

void calculator();
void printSum(int num1, int num2);
void printSub(int num1, int num2);
void printMul(int num1, int num2);
void printDiv(int num1, int num2);

int main() {
        calculator();
        return 0;
}

void calculator(){
        int num1, num2;
        char op;

        while(1) {
                printf("숫자 두 개와 연산자를 입력하세요.(끝내려면 \"0 0 x\"을 입력하세요)\n");
                scanf("%d %d %c", &num1, &num2, &op);
                if (num1 == 0 && num2 == 0 && op == 'x') {
                        break;
                }
                switch (op) {
                case '+': printSum(num1, num2);
                        break;
                case '-': printSub(num1, num2);
                        break;
                case '*': printMul(num1, num2);
                        break;
                case '/': printDiv(num1, num2);
                        break;
                }
        }
        return;
}
void printSum(int num1, int num2) {
        printf("SUM : %d\n", num1 + num2);
        return;
}
void printSub(int num1, int num2) {
        printf("SUB : %d\n", num1 - num2);
        return;
}
void printMul(int num1, int num2) {
        printf("MUL : %d\n", num1 * num2);
        return;
}
void printDiv(int num1, int num2) {
        printf("DIV : %lf\n", (double)num1 / num2);
        return;
}
