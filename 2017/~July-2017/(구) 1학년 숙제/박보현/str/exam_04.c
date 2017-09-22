#include <stdio.h>

char strncat(char *a, char *b, int c)
{
	int i = 0, j = 0;

	while (a[i] != '\0')
	{
		i++;
	}

	while (j < c)
	{
		a[i++] = b[j++];
	}
}

int exam_04()
{
	char a[50] = "BB ", b[] = "puzzle ";

	strncat(a, b, 4);

	printf("%s\n", a);
}