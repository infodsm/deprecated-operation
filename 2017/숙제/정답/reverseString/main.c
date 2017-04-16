#include <stdio.h>
#define MAX_LEN 20

int main() {
    int index = 0;
    char str[MAX_LEN];
    char c = 0;

    scanf("%s %c", str, &c);

    while(str[index] != '\0') {
        if(str[index]==c){
            break;
        }
        index++;
    }

    printf("%d\n", index);
}
